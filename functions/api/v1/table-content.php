<?php

function archivescms_get_table_content() {
  $order = array(
    'fixed_slug',
    'title',
    'section',
    'page',
    'article',
    'bylines...',
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

    $issue_content = get_post_meta($q->post->ID, 'article_content', true);
    if (empty($issue_content))
      $issue_content = '[]';

    $ic_decoded = json_decode($issue_content);
    for ($i = 0; $i < count($ic_decoded); $i++) {
      // per section
      if ($i > 0)
        echo "\t\t";

      echo $ic_decoded[$i]->name . "\t";
      for ($j = 0; $j < count($ic_decoded[$i]->articles); $j++) {
        // per article
        if ($j > 0)
          echo "\t\t\t";

        echo $ic_decoded[$i]->articles[$j]->page . "\t";
        echo $ic_decoded[$i]->articles[$j]->title . "\t";
        for ($k = 0; $k < count($ic_decoded[$i]->articles[$j]->bylines); $k++) {
          echo $ic_decoded[$i]->articles[$j]->bylines[$k] . "\t";
        }
        if ($j != count($ic_decoded[$i]->articles) - 1 || $i != count($ic_decoded) - 1)
          echo PHP_EOL;
      }
    }
    
    // echo $issue_content . "\t";

    echo PHP_EOL;
  }

  return;
}

?>