<?php

include 'templates/admin/post/show_meta_box.php';

include 'functions/custom-meta.php';


/**
 * Disables WordPress admin bar
 */
add_theme_support('admin-bar', array( 'callback' => '__return_false'));


function archivescms_get_articles() {
  $articles = array();

  $query = new WP_Query(array(
    'posts_per_page' => 20,
  ));

  while ($query->have_posts()) {
    $query->the_post();
    
    $bylines = array();
    $bylines_raw = array_values(array_filter(array_map(
      fn($val): string => trim($val),
      preg_split("/\r\n|\n|\r/", get_post_meta($query->post->ID, 'bylines', true)),
    )));

    for ($i = 0; $i < count($bylines_raw); $i++) {
      if ($i % 2 == 1 || $i + 1 >= count($bylines_raw)) continue;

      $bylines[] = array(
        'key' => $bylines_raw[$i],
        'value' => $bylines_raw[$i + 1],
      );
    }

    $categories = array();
    $categories_raw = get_the_category();
    foreach ($categories_raw as $categ) {
      $categories[] = $categ->slug;
    }

    $articles[] = array(
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

  wp_reset_postdata();

  return rest_ensure_response($articles);
}


add_action('rest_api_init', function() {
  register_rest_route('api/v1', 'issues', array(
      'methods' => 'GET',
      'callback' => 'archivescms_get_articles'
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