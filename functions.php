<?php

include 'templates/admin/post/show_meta_box.php';

include 'functions/custom-meta.php';


/**
 * CORS
 */
// function add_cors_http_header() {
//   header("Access-Control-Allow-Origin: *");
// }

function init_cors( $value ) {
$origin = get_http_origin();
$allowed_origins = ['theguidon.github.io', 'localhost:5173', 'dev.theguidon.com', 'archives.theguidon.com'];

if ( $origin && in_array( $origin, $allowed_origins ) ) {
  header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $origin ) );
  header( 'Access-Control-Allow-Methods: GET' );
  header( 'Access-Control-Allow-Credentials: true' );
}

return $value;
}
// add_action('init', 'init_cors');


/**
 * Disables WordPress admin bar
 */
add_theme_support('admin-bar', array( 'callback' => '__return_false'));


function archivescms_issue_response($query) {
  $categories = array();
  $categories_raw = get_the_category();
  foreach ($categories_raw as $categ) {
    $categories[] = $categ->slug;
  }

  $volume_num = get_post_meta($query->post->ID, 'volume_num', true);
  $issue_num = get_post_meta($query->post->ID, 'issue_num', true);
  $article_content = get_post_meta($query->post->ID, 'article_content', true);

  return array(
    'fixed_slug' => get_post_meta($query->post->ID, 'fixed_slug', true),
    'title' => get_the_title(),
    'date_published' => get_the_date('c'),
    'description' => get_post_meta($query->post->ID, 'description', true),
    'is_legacy' => get_post_meta($query->post->ID, 'is_legacy', true) == "true" ? true : false,
    'unsure_date' => get_post_meta($query->post->ID, 'unsure_date', true) == "true" ? true : false,
    'num_pages' => intval(get_post_meta($query->post->ID, 'num_pages', true)),
    'shortlink' => get_post_meta($query->post->ID, 'shortlink', true),
    'volume_num' => empty($volume_num) ? null : intval($volume_num),
    'issue_num' => empty($issue_num) ? null : intval($issue_num),
    'cover' => wp_get_attachment_image_src(get_post_thumbnail_id($query->post->ID), 'large')[0],
    'cover_full' => wp_get_attachment_url(get_post_thumbnail_id($query->post->ID)),
    'cover_medium' => wp_get_attachment_image_src(get_post_thumbnail_id($query->post->ID), 'medium')[0],
    'cover_thumbnail' => wp_get_attachment_image_src(get_post_thumbnail_id($query->post->ID), 'thumbnail')[0],
    // 'full_issue' => get_post_meta($query->post->ID, 'full_issue', true),
    'full_issue' => '/issues/' . get_the_date('Y') . '/' . get_post_meta($query->post->ID, 'fixed_slug', true) . '.pdf',
    'article_content' => empty($article_content) ? '[]' : $article_content,
    'categories' => $categories,
  );
}


function archivescms_get_issues($req) {
  $args = array(
    'posts_per_page' => 20,
  );

  $isLegacy = $req->get_param('legacy');
  if (isset($isLegacy) && $isLegacy == 'true') {
    $args['meta_query'] = array(
      array(
        'key' => 'is_legacy',
        'value' => 'true',
        'compare' => '=',
      ),
    );
  }

  $categ = $req->get_param('categ');
  if (isset($categ) && $categ != 'legacy')
    $args['category_name'] = $categ;

  $page = $req->get_param('page');
  $args['paged'] = isset($page) && is_numeric($page) ? intval($page) : 1;

  $order = $req->get_param('order');
  if (isset($order) && ($order == 'asc' || $order == 'desc'))
    $args['order'] = $order;

  $search = $req->get_param('search');
  if (isset($search)) {
    $args['search_query'] = $search;
    $args['meta_query'] = array(
      array(
        'key' => 'article_content',
        'value' => $search,
        'compare' => 'LIKE',
      ),
    );
  }

  $year = $req->get_param('year');
  if (isset($year) && is_numeric($year)) {
    $args['date_query'] = array(
      array(
        'after' => array('year' => $year, 'month' => '1', 'day' => '1'),
        'before' => array('year' => $year, 'month' => '12', 'day' => '31'),
        'inclusive' => true,
      ),
    );
  }

  $query = new WP_Query($args);
  $issues = array();

  while ($query->have_posts()) {
    $query->the_post();
    $issues[] = archivescms_issue_response($query);
  }
  wp_reset_postdata();

  return rest_ensure_response(array(
    'page' => isset($page) ? intval($page) : 1,
    'max_pages' => $query->max_num_pages,
    'order' => isset($order) ? (($order == 'asc' || $order == 'desc') ? $order : 'desc') : 'desc',
    'categ' => $isLegacy == 'true' ? 'legacy' : $categ,
    'year' => isset($year) ? intval($year) : null,
    'search' => $search,
    'found' => $query->found_posts,
    'issues' => $issues,
  ));
}


function archivescms_get_issue($req) {
  $issue = array();

  $query = new WP_Query(array(
    'meta_query' => array(
      array('key' => 'fixed_slug', 'value' => $req['slug']),
    ),
  ));

  if ($query->have_posts()) {
    $query->the_post();
    $issue = archivescms_issue_response($query);
  }
  wp_reset_postdata();

  return rest_ensure_response($issue);
}


function archivescms_get_random_covers() {
  $covers = array(
    'legacy' => array(),
  );

  $categs = array('press-issue', 'graduation-magazine', 'freshmanual', 'uaap-primer', 'other');
  
  foreach ($categs as $categ) {
    $q = new WP_Query(array(
      'posts_per_page' => 2,
      'orderby' => 'rand',
      'category_name' => $categ,
      'meta_query' => array(
        'relation' => 'OR',
        array(
          'key' => 'is_legacy',
          'value' => 'true',
          'compare' => '!=',
        ),
        array(
          'key' => 'is_legacy',
          'compare' => 'NOT EXISTS',
        ),
      ),
    ));

    $covers[$categ] = array();

    while ($q->have_posts()) {
      $q->the_post();
      $covers[$categ][] = wp_get_attachment_image_src(get_post_thumbnail_id($standard_query->post->ID), 'large')[0];
    }
    wp_reset_postdata();
  }

  $legacy_query = new WP_Query(array(
    'posts_per_page' => 2,
    'orderby' => 'rand',
    'meta_query' => array(
      array(
        'key' => 'is_legacy',
        'value' => 'true',
        'compare' => '=',
      )
    ),
  ));

  while ($legacy_query->have_posts()) {
    $legacy_query->the_post();
    $covers['legacy'][] = wp_get_attachment_image_src(get_post_thumbnail_id($legacy_query->post->ID), 'large')[0];
  }
  wp_reset_postdata();

  // TODO REMOVE BEFORE PRODUCTION
  for ($i = 0; $i < 2; $i++) {
    if (count($covers['legacy']) <= $i) {
      $covers['legacy'][] = $covers['legacy'][0];
    }
  }

  return rest_ensure_response($covers);
}


function archivescms_get_minmax() {
  $earliest = get_posts(array(
    'numberposts' => 1,
    'order_by' => 'publish_date',
    'order' => 'ASC',
  ));

  return rest_ensure_response(array(
    'min' => intval(get_the_date("Y", $earliest[0]->ID)),
    'max' => intval(date("Y")),
  ));
}


add_action('rest_api_init', function() {
  /**
   * GET /issues
   * Get latest issues
   * ?search
   * ?categ
   * ?page
   * ?from
   * ?to
   * ?sort
   */
  register_rest_route('api/v1', 'issues', array(
      'methods' => 'GET',
      'callback' => 'archivescms_get_issues'
  ));

  /**
   * GET /issue/:slug
   */
  register_rest_route('api/v1', 'issue/(?P<slug>[a-zA-Z0-9-_]+)', array(
    'methods' => 'GET',
    'callback' => 'archivescms_get_issue',
  ));

  /**
   * GET /random-covers
   */
  register_rest_route('api/v1', 'random-covers', array(
    'methods' => 'GET',
    'callback' => 'archivescms_get_random_covers',
  ));

  /**
   * GET /minmax-years
   */
  register_rest_route('api/v1', 'minmax', array(
    'methods' => 'GET',
    'callback' => 'archivescms_get_minmax',
  ));
});


// POST THUMBNAIL FUNCTIONS
add_theme_support('post-thumbnails'); 
add_filter('post_thumbnail_html', 'my_post_image_html', 10, 3);

// adds the permalink automatically to the featured image, based on Wordpress Codex
function my_post_image_html($html, $post_id, $post_image_id) {
  $html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . $html . '</a>';
  return $html;
}


/**
 * https://wordpress.stackexchange.com/questions/178484/wp-query-args-title-or-meta-value
 */
add_action('pre_get_posts', function($query) {
  if ($search_query = $query->get('search_query') )   {
    add_filter('get_meta_sql', function($sql) use ($search_query) {
      global $wpdb;

      // Only run once:
      static $nr = 0; 
      if (0 != $nr++) return $sql;

      // Modify WHERE part:
      $sql['where'] = sprintf(
        " AND (%s OR %s) ",
        $wpdb->prepare("{$wpdb->posts}.post_title LIKE %s", '%' . $search_query . '%'),
        mb_substr($sql['where'], 5, mb_strlen($sql['where']))
      );
      return $sql;
    });
  }
});


/**
 * Main CSS
 */
function archivescms_register_files() {
  $version = wp_get_theme()->get('Version');

  wp_enqueue_style('styles-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), $version);

  wp_enqueue_script('jquery', get_template_directory_uri() . 'assets/js/jquery-3.6.0.min.js', array(), $version);
  wp_enqueue_script('scripts-admin', get_template_directory_uri() . '/assets/js/admin.js', array('jquery'), $version);
}

add_action('admin_enqueue_scripts', 'archivescms_register_files');


/**
 * remove texturize
 */
remove_filter('the_title', 'wptexturize');

?>