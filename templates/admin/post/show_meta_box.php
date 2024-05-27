<?php

function archivescms_issue_show_meta_box() {
  global $post;

  $description = get_post_meta($post->ID, 'description', true);
  $path = get_post_meta($post->ID, 'path', true);
  $bylines = get_post_meta($post->ID, 'bylines', true);
  $shortlink = get_post_meta($post->ID, 'shortlink', true);

  $preview_image = get_post_meta($post->ID, 'preview_image', true);
  $preview_video = get_post_meta($post->ID, 'preview_video', true);
?>

<input
  type="hidden"
  name="custom_nonce"
  id="custom_nonce"
  value="<?php echo wp_create_nonce(basename(__FILE__)) ?>"
/>

<label for="description">Description</label><br />
<textarea
  class="widefat"
  type="text"
  name="description"
  id="description"
  rows="5"
  style="resize: none"
><?php echo $description ?></textarea><br />

<label for="path">Path</label><br />
<input
  class="widefat"
  type="text"
  name="path"
  id="path"
  value="<?php echo $path ?>"
/><br /><br />

<label for="bylines">Credits (Sample format below):</label>
<p style="margin-top: 0; margin-bottom: 0; padding-left: 16px;">
  Written by <br />
  [Author 1], [Author 2], and [Author 3] <br />
  Photos by <br />
  [Photographer 1] <br />
  Designed by <br />
  [Designer 1] and [Designer 2]
</p>
<textarea
  class="widefat"
  type="text"
  name="bylines"
  id="bylines"
  rows="10"
  style="resize: none"
><?php echo $bylines ?></textarea><br />

<label for="shortlink">Shortlink URL (tgdn.co)</label><br />
<input
  class="widefat"
  type="url"
  name="shortlink"
  id="shortlink"
  value="<?php echo $shortlink ?>"
/><br />

<label for="preview_image">Upload Preview Image</label><br />
<input
  class="widefat"
  type="file"
  accept="image/*"
  name="preview_image"
  id="preview_image"
/>

<?php
if (!empty($preview_image)) {
?>
  <img
    style="display: block; width: 100%; max-width: 400px; height: auto;"
    src="<?php echo $preview_image ?>"
  />
<?php
}
?>

<label for="preview_video">Upload Preview Video</label><br />
<input
  class="widefat"
  type="file"
  accept="video/*"
  name="preview_video"
  id="preview_video"
/>

<?php
if (!empty($preview_video)) {
?>
  <video
    style="display: block; width: 100%; max-width: 400px; height: auto;"
  >
    <source src="<?php echo $preview_video ?>" />
  </video>
<?php
}
?>


<?php
}

?>