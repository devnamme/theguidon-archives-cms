<?php

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

?>