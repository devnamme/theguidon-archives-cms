<?php

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

  $volume = $req->get_param('volume');
  $search = $req->get_param('search');
  if (isset($volume)) {
    $args['meta_query'] = array(
      array(
        'key' => 'volume_num',
        'value' => $volume,
      ),
    );
  } else if (isset($search)) {
    $args['search_query'] = $search;
    $args['meta_query'] = array(
      'relation' => 'OR',
      array(
        'key' => 'article_content',
        'value' => $search,
        'compare' => 'LIKE',
      ),
      array(
        'key' => 'contribs',
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


  $date_query = array(
    'inclusive' => true,
  );

  $from = $req->get_param('from');
  if (isset($from)) {
    $exp = explode('-', $from);
    // if (count($exp) == 1)
    //   $date_query['after'] = array('year' => $exp[0], 'month' => 1, 'day' => 1);
    // else if (count($exp) == 2)
    //   $date_query['after'] = array('year' => $exp[0], 'month' => $exp[1], 'day' => 1);
    // else
    if (count($exp) >= 3)
      $date_query['after'] = array('year' => $exp[0], 'month' => $exp[1], 'day' => $exp[2]);
  }

  $until = $req->get_param('until');
  if (isset($until)) {
    $exp = explode('-', $until);
    // if (count($exp) == 1)
    //   $date_query['before'] = array('year' => $exp[0], 'month' => 12, 'day' => 31);
    // else if (count($exp) == 2)
    //   $date_query['before'] = array('year' => $exp[0], 'month' => $exp[1]);
    // else
    if (count($exp) >= 3)
      $date_query['before'] = array('year' => $exp[0], 'month' => $exp[1], 'day' => $exp[2]);
  }

  if (count($date_query) > 1) {
    $args['date_query'] = array($date_query);
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
    'from' => $from,
    'until' => $until,
    'volume' => $volume,
    'search' => $search,
    'found' => $query->found_posts,
    'issues' => $issues,
  ));
}

?>