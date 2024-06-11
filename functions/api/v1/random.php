<?php

function archivescms_get_random() {
  $rnd = array(
    'titles' => array(),
    'authors' => array(),
    'volumes' => array(),
    'covers' => array(),
  );
  
  // get 10 titles
  $query = new WP_Query(array(
    'posts_per_page' => 10,
    'orderby' => 'rand',
  ));

  while ($query->have_posts()) {
    $query->the_post();
    $rnd['titles'][] = get_the_title($query->post->ID);
  }
  wp_reset_postdata();


  // get 5 issues with article_content
  // for each issue, return 2 authors
  $query = new WP_Query(array(
    'posts_per_page' => 5,
    'orderby' => 'rand',
    'meta_query' => array(
      array(
        'key' => 'article_content',
        'compare' => 'EXISTS',
      ),
    ),
  ));

  while ($query->have_posts()) {
    $query->the_post();
    $article_content = get_post_meta($query->post->ID, 'article_content', true);

    $exp = explode("\"bylines\"", $article_content);
    for ($i = 0; $i < 2; $i++) {
      $idx = rand(1, count($exp) - 1);
      $bylines = explode("]", explode("[", $exp[$idx])[1])[0];

      $exp2 = explode(",", $bylines);
      $idx2 = rand(0, count($exp2) - 1);
      $rnd['authors'][] = explode("\"", $exp2[$idx2])[1];
    }
  }


  // get 5 articles with volume_num
  $query = new WP_Query(array(
    'posts_per_page' => 5,
    'orderby' => 'rand',
    'meta_query' => array(
      array(
        'key' => 'volume_num',
        'compare' => 'EXISTS',
      ),
    ),
  ));

  while ($query->have_posts()) {
    $query->the_post();
    $rnd['volumes'][] = get_post_meta($query->post->ID, 'volume_num', true);
  }
  wp_reset_postdata();


  // get 2 covers per categ
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

    $rnd['covers'][$categ] = array();
    while ($q->have_posts()) {
      $q->the_post();
      $rnd['covers'][$categ][] = wp_get_attachment_image_src(get_post_thumbnail_id($q->post->ID), 'large')[0];
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
    $rnd['covers']['legacy'][] = wp_get_attachment_image_src(get_post_thumbnail_id($legacy_query->post->ID), 'large')[0];
  }
  wp_reset_postdata();

  // TODO REMOVE BEFORE PRODUCTION
  for ($i = 0; $i < 2; $i++) {
    if (count($rnd['covers']['legacy']) <= $i) {
      $rnd['covers']['legacy'][] = $rnd['covers']['legacy'][0];
    }
  }

  return rest_ensure_response($rnd);
}

?>