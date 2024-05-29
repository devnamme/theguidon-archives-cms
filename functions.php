<?php

include 'templates/admin/post/show_meta_box.php';

include 'functions/custom-meta.php';


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

  return array(
    'fixed_slug' => get_post_meta($query->post->ID, 'fixed_slug', true),
    'title' => get_the_title(),
    'date_published' => get_the_date('c'),
    'description' => get_post_meta($query->post->ID, 'description', true),
    'is_legacy' => get_post_meta($query->post->ID, 'is_legacy', true) == "true" ? true : false,
    'num_pages' => get_post_meta($query->post->ID, 'num_pages', true),
    'shortlink' => get_post_meta($query->post->ID, 'shortlink', true),
    'cover' => wp_get_attachment_url(get_post_thumbnail_id($query->post->ID)),
    'full_issue' => get_post_meta($query->post->ID, 'full_issue', true),
    'content' => get_post_meta($query->post->ID, 'content', true),
    'categories' => $categories,
  );
}


function archivescms_get_issues($req) {
  $args = array(
    'posts_per_page' => 20,
  );

  $categ = $req->get_param('categ');
  if (isset($categ))
    $args['category_name'] = $categ;

  $page = $req->get_param('page');
  if (isset($page))
    $args['paged'] = intval($page);


  $query = new WP_Query($args);
  $issues = array();

  while ($query->have_posts()) {
    $query->the_post();
    $issues[] = archivescms_issue_response($query);
  }
  wp_reset_postdata();

  return rest_ensure_response(array(
    'page' => intval($page),
    'max_pages' => $query->max_num_pages,
    'categ' => $categ,
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
   * ?categ
   * ?page
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
});


// POST THUMBNAIL FUNCTIONS
add_theme_support('post-thumbnails'); 
add_filter('post_thumbnail_html', 'my_post_image_html', 10, 3);

// adds the permalink automaticall to the featured image, based on Wordpress Codex
function my_post_image_html($html, $post_id, $post_image_id) {
  $html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . $html . '</a>';
  return $html;
}

?>