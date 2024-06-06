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

  return array(
    'fixed_slug' => get_post_meta($query->post->ID, 'fixed_slug', true),
    'title' => get_the_title(),
    'date_published' => get_the_date('c'),
    'description' => get_post_meta($query->post->ID, 'description', true),
    'is_legacy' => get_post_meta($query->post->ID, 'is_legacy', true) == "true" ? true : false,
    'num_pages' => intval(get_post_meta($query->post->ID, 'num_pages', true)),
    'shortlink' => get_post_meta($query->post->ID, 'shortlink', true),
    'volume_num' => empty($volume_num) ? null : intval($volume_num),
    'issue_num' => empty($issue_num) ? null : intval($issue_num),
    'cover_full' => wp_get_attachment_url(get_post_thumbnail_id($query->post->ID)),
    'cover' => wp_get_attachment_image_src(get_post_thumbnail_id($query->post->ID), 'large')[0],
    // 'full_issue' => get_post_meta($query->post->ID, 'full_issue', true),
    'full_issue' => '/issues/' . get_the_date('Y') . '/' . get_post_meta($query->post->ID, 'fixed_slug', true) . '.pdf',
    'content' => get_post_meta($query->post->ID, 'content', true),
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
  $args['paged'] = isset($page) ? intval($page) : 1;

  $order = $req->get_param('order');
  if (isset($order) && ($order == 'asc' || $order == 'desc'))
    $args['order'] = $order;

  $search = $req->get_param('search');
  if (isset($search))
    $args['search_query'] = $search;

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
   * GET /legacy
   * ?categ
   * ?page
   */
  // register_rest_route('api/v1', 'legacy', array(
  //   'methods' => 'GET',
  //   'callback' => 'archivescms_get_legacy',
  // ));

  /**
   * GET /search
   */
});


// POST THUMBNAIL FUNCTIONS
add_theme_support('post-thumbnails'); 
add_filter('post_thumbnail_html', 'my_post_image_html', 10, 3);

// adds the permalink automatically to the featured image, based on Wordpress Codex
function my_post_image_html($html, $post_id, $post_image_id) {
  $html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . $html . '</a>';
  return $html;
}


add_filter('posts_where', 'archivescms_posts_where', 10, 2);
function archivescms_posts_where($where, $wp_query) {
  global $wpdb;
  if ($search_query = $wp_query->get('search_query')) {
    $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($wpdb->esc_like($search_query)) . '%\'';
  }
  return $where;
}


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

?>