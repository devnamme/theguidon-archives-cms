<?php

function archivescms_issue_show_meta_box() {
  global $post;

  $custom_meta = array();
  $keys = array(
    'fixed_slug',
    'description',
    'is_legacy',
    'num_pages',
    'shortlink',
    'full_issue',
    'content',
  );

  foreach ($keys as $key) {
    $custom_meta[$key] = get_post_meta($post->ID, $key, true);
  }
?>

<input
  type="hidden"
  name="custom_nonce"
  id="custom_nonce"
  value="<?php echo wp_create_nonce(basename(__FILE__)) ?>"
/>

<label for="fixed_slug">Fixed Slug</label><br />
<input
  class="widefat"
  type="text"
  name="fixed_slug"
  id="fixed_slug"
  value="<?php echo $custom_meta['fixed_slug'] ?>"
/><br /><br />

<label for="description">Description</label><br />
<textarea
  class="widefat"
  type="text"
  name="description"
  id="description"
  rows="5"
  style="resize: none"
><?php echo $custom_meta['description'] ?></textarea><br /><br />

<label for="num_pages">Number of Pages</label><br />
<input
  class="widefat"
  type="number"
  name="num_pages"
  id="num_pages"
  value="<?php echo $custom_meta['num_pages'] ?>"
/><br /><br />

<label for="shortlink">Shortlink URL (tgdn.co)</label><br />
<input
  class="widefat"
  type="url"
  name="shortlink"
  id="shortlink"
  value="<?php echo $custom_meta['shortlink'] ?>"
/><br /><br />

<label for="full_issue">Upload Full Issue (PDF)</label><br />
<input
  class="widefat"
  type="file"
  accept="application/pdf"
  name="full_issue"
  id="full_issue"
/>

<?php
if (has_post_thumbnail($post) && !empty($custom_meta['full_issue'])) {
?>
  <img
    style="display: block; width: 100%; max-width: 200px; height: auto;"
    src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID)) ?>"
  />
<?php
}
?>

<br /><br />

<label for="content">Content (Sample format below):</label>
<p style="margin-top: 0; margin-bottom: 0; padding-left: 16px;">
  [Title 1] <br />
  [Author 1] <br />
  [Title 2] <br />
  [Author 1], [Author 2], and [Author 3]
</p>
<textarea
  class="widefat"
  type="text"
  name="content"
  id="content"
  rows="10"
  style="resize: none"
><?php echo $custom_meta['content'] ?></textarea><br /><br />

<?php
}

?>