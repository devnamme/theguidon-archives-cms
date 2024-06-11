<?php

function archivescms_get_minmax() {
  $earliest = get_posts(array(
    'numberposts' => 1,
    'order_by' => 'publish_date',
    'order' => 'ASC',
  ));

  return rest_ensure_response(array(
    'min' => get_the_date("c", $earliest[0]->ID),
    'max' => date("c"),
  ));
}

?>