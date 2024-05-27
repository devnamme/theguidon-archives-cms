<?php

include 'templates/admin/post/show_meta_box.php';

include 'functions/custom-meta.php';


/**
 * Disables WordPress admin bar
 */
add_theme_support('admin-bar', array( 'callback' => '__return_false'));


function intcms_get_articles() {
  $articles = array();

  $query = new WP_Query(array(
    'numberposts' => -1,
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
      'slug' => $query->post->post_name,
      'title' => get_the_title(),
      'date_published' => get_the_date('c'),
      'description' => get_post_meta($query->post->ID, 'description', true),
      'path' => get_post_meta($query->post->ID, 'path', true),
      'cover' => wp_get_attachment_url(get_post_thumbnail_id($query->post->ID)),
      'preview_image' => get_post_meta($query->post->ID, 'preview_image', true),
      'preview_video' => get_post_meta($query->post->ID, 'preview_video', true),
      'bylines' => $bylines,
      'categories' => $categories,
    );
  }

  wp_reset_postdata();

  return rest_ensure_response($articles);
}


add_action('rest_api_init', function() {
  register_rest_route('wp/v2', 'interactives', array(
      'methods' => 'GET',
      'callback' => 'intcms_get_articles'
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