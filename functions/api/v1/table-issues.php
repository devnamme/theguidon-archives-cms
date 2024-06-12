<?php

function archivescms_get_table_issues() {
  $order = array(
    'title',
    'fixed_slug',
    'date_published',
    'description',
    'is_legacy',
    'unsure_date',
    'num_pages',
    'shortlink',
    'volume_num',
    'issue_num',
    'cover',
    'cover_full',
    'cover_medium',
    'cover_thumbnail',
    'full_issue',
    'categories',
    'has_content',
    'has_contributors',
  );

  echo implode("\t", $order) . PHP_EOL;

  $q = new WP_Query(array(
    'order' => 'asc',
    'posts_per_page' => -1,
  ));

  while ($q->have_posts()) {
    $q->the_post();

    echo get_the_title($q->post->ID) . "\t";
    echo get_post_meta($q->post->ID, 'fixed_slug', true) . "\t";
    echo get_the_date('c', $q->post->ID) . "\t";
    # TODO FIX
    echo preg_replace('/\s+/', ' ', get_post_meta($q->post->ID, 'description', true)) . "\t";
    echo (get_post_meta($q->post->ID, 'is_legacy', true) == "true" ? "true" : "false") . "\t";
    echo (get_post_meta($q->post->ID, 'unsure_date', true) == "true" ? "true" : "false") . "\t";
    echo intval(get_post_meta($q->post->ID, 'num_pages', true)) . "\t";
    echo get_post_meta($q->post->ID, 'shortlink', true) . "\t";
    echo get_post_meta($q->post->ID, 'volume_num', true) . "\t";
    echo get_post_meta($q->post->ID, 'issue_num', true) . "\t";
    echo wp_get_attachment_image_src(get_post_thumbnail_id($q->post->ID), 'large')[0] . "\t";
    echo wp_get_attachment_url(get_post_thumbnail_id($q->post->ID)) . "\t";
    echo wp_get_attachment_image_src(get_post_thumbnail_id($q->post->ID), 'medium')[0] . "\t";
    echo wp_get_attachment_image_src(get_post_thumbnail_id($q->post->ID), 'thumbnail')[0] . "\t";
    echo '/issues/' . get_the_date('Y') . '/' . get_post_meta($q->post->ID, 'fixed_slug', true) . ".pdf\t";
    echo get_the_category($q->post->ID)[0]->slug . "\t";
    echo (empty(get_post_meta($q->post->ID, 'article_content', true)) ? 'false' : 'true') . "\t";
    echo (empty(get_post_meta($q->post->ID, 'contribs', true)) ? 'false' : 'true') . "\t";
    echo PHP_EOL;
  }

  return;
}

?>