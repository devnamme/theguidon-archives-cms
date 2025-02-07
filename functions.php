<?php

include 'templates/admin/post/show_meta_box.php';

include 'functions/custom-meta.php';

include 'functions/api/v1/issues.php';
include 'functions/api/v1/issue.php';
include 'functions/api/v1/minmax.php';
include 'functions/api/v1/random.php';
include 'functions/api/v1/table-issues.php';
include 'functions/api/v1/table-content.php';
include 'functions/api/v1/table-contributors.php';
include 'functions/api/v1/add-legacy.php';


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
  $contributors = get_post_meta($query->post->ID, 'contribs', true);

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
    'issue_content' => empty($article_content) ? '[]' : $article_content,
    'contributors' => empty($contributors) ? '[]' : $contributors,
    'categories' => $categories,
  );
}


add_action('rest_api_init', function() {
  /**
   * GET /issues
   * ?legacy    boolean   true, false
   * ?categ     string    press-issue, graduation-magazine, freshmanual, uaap-primer, other
   * ?page      integer
   * ?order     string    asc, desc
   * ?volume    integer
   * ?search    string
   * ?year      integer
   * ?from      string    YYYY-MM-DD
   * ?until     string    YYYY-MM-DD
   * 
   */
  register_rest_route('api/v1', 'issues', array(
      'methods' => 'GET',
      'callback' => 'archivescms_get_issues'
  ));

  /**
   * GET /issue/:slug
   * :slug      string
   * 
   */
  register_rest_route('api/v1', 'issue/(?P<slug>[a-zA-Z0-9-_]+)', array(
    'methods' => 'GET',
    'callback' => 'archivescms_get_issue',
  ));

  /**
   * GET /minmax-years
   */
  register_rest_route('api/v1', 'minmax', array(
    'methods' => 'GET',
    'callback' => 'archivescms_get_minmax',
  ));

  /**
   * GET /random
   */
  register_rest_route('api/v1', 'random', array(
    'methods' => 'GET',
    'callback' => 'archivescms_get_random',
  ));

  /**
   * GET /table/issues
   */
  register_rest_route('api/v1', 'table/issues', array(
    'methods' => 'GET',
    'callback' => 'archivescms_get_table_issues',
  ));

  /**
   * GET /table/content
   */
  register_rest_route('api/v1', 'table/content', array(
    'methods' => 'GET',
    'callback' => 'archivescms_get_table_content',
  ));

  /**
   * GET /table/contributors
   */
  register_rest_route('api/v1', 'table/contributors', array(
    'methods' => 'GET',
    'callback' => 'archivescms_get_table_contributors',
  ));

  /**
   * GET /experimental/add
   */
  register_rest_route('api/v1', 'experimental/add', array(
    'methods' => 'GET',
    'callback' => 'archivescms_add_legacy',
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