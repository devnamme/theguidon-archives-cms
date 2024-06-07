<?php

/**
 * Add meta boxes
 */
function archivescms_add_links_meta_box() {
  add_meta_box(
    'archivescms_issue_meta_box',
    'Issue Information',
    'archivescms_issue_show_meta_box',
    array('post'),
    'advanced',
    'high',
  );
}
add_action('add_meta_boxes', 'archivescms_add_links_meta_box');


/**
 * Helper for saving post custom meta
 */
function archivescms_save_post_custom_meta($post_id, $meta_keys) {
  foreach ($meta_keys as $key) {
    $old = get_post_meta($post_id, $key, true);

    if (isset($_POST[$key]))
      $new = $_POST[$key];
    else
      $new = '';

    if ($new && $new != $old)
      update_post_meta($post_id, $key, $new);
    else if ('' == $new && $old)
      delete_post_meta($post_id, $key, $old);
  }
}


/**
 * Helper for saving files
 */
function archivescms_save_post_custom_meta_files($post_id, $post_slug, $file_keys) {
  foreach($file_keys as $key) {
    if (isset($_FILES[$key[0]])) {
      if ($_FILES[$key[0]]['error'] == 4 || ($_FILES[$key[0]]['size'] == 0 && $_FILES[$key[0]]['error'] == 0)) {
        continue;
      }

      $filename = $_FILES[$key[0]]['name'];
      $upload_dir = wp_upload_dir();

      $newfilename = $key[1] . '-' . $post_slug . '.' . pathinfo($filename)['extension'];
  
      if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $newfilename;
      } else {
        $file = $upload_dir['basedir'] . '/' . $newfilename;
      }
  
      move_uploaded_file($_FILES[$key[0]]['tmp_name'], $file);
  
      $wp_filetype = wp_check_filetype($filename, null);
  
      $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit',
      );
  
      $attach_id = wp_insert_attachment($attachment, $file, $post_id);
      require_once(ABSPATH . 'wp-admin/includes/image.php');
      $attach_data = wp_generate_attachment_metadata($attach_id, $file);
      wp_update_attachment_metadata($attach_id, $attach_data);
  
      update_post_meta($post_id, $key[0], wp_get_attachment_url($attach_id));
    }
  }
}

/**
 * Save custom meta
 */
function archivescms_issue_save_custom_meta($post_id, $post, $update) {
  if (!isset($_POST['custom_nonce']))
    return;

  archivescms_save_post_custom_meta($post_id, array(
    'fixed_slug',
    'description',
    'is_legacy',
    'unsure_date',
    'num_pages',
    'shortlink',
    'volume_num',
    'issue_num',
    'article_content',
  ));

  archivescms_save_post_custom_meta_files($post_id, $_POST['fixed_slug'], array(
    array('full_issue', 'issue'),
  ));

}
add_action('save_post_post', 'archivescms_issue_save_custom_meta', 10, 3);

?>