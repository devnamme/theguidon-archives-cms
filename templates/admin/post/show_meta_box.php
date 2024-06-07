<?php

function archivescms_issue_show_meta_box() {
  global $post;

  $custom_meta = array();
  $keys = array(
    'fixed_slug',
    'description',
    'is_legacy',
    'unsure_date',
    'num_pages',
    'shortlink',
    'volume_num',
    'issue_num',
    // 'full_issue',
    'article_content',
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

<input
  type="checkbox"
  id="is_legacy"
  name="is_legacy"
  value="true"
  <?php if (strtolower(trim($custom_meta['is_legacy'])) == "true") { echo "checked"; } ?>
/>
<label for="is_legacy">Is Legacy?</label><br />

<input
  type="checkbox"
  id="unsure_date"
  name="unsure_date"
  value="true"
  <?php if (strtolower(trim($custom_meta['unsure_date'])) == "true") { echo "checked"; } ?>
/>
<label for="unsure_date">Unsure published date?</label><br /><br />

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

<div style="display: flex; flex-flow: row wrap; row-gap: 8px; column-gap: 16px;">
  <div style="flex-basis: 100px; flex-grow: 1;">
    <label for="volume_num">Volume No.</label><br />
    <input
      class="widefat"
      type="number"
      name="volume_num"
      id="volume_num"
      value="<?php echo $custom_meta['volume_num'] ?>"
    />
  </div>
  <div style="flex-basis: 100px; flex-grow: 1;">
    <label for="issue_num">Issue No.</label><br />
    <input
      class="widefat"
      type="number"
      name="issue_num"
      id="issue_num"
      value="<?php echo $custom_meta['issue_num'] ?>"
    />
  </div>
</div>

<br />

<!-- <label for="full_issue">Upload Full Issue (PDF)</label><br />
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

<br /><br /> -->

<label for="content">Content (in JSON string)</label>
<p style="margin: 0; color: red;">
  Do not edit this unless you know what you're doing. Editing this manually will override the UI below. Please the documentation on <a href="https://github.com/theguidon/theguidon-archives" target="_blank" style="color: inherit">GitHub</a> to find relevant information on making this work.
</p>
<textarea
  class="widefat"
  type="text"
  name="article_content"
  id="article_content"
  rows="10"
  style="resize: none; font-family: monospace;"
  oninput="acms_content_queue_override()"
><?php echo $custom_meta['article_content'] ?></textarea><br /><br />

<label>Content (user-friendly)</label>
<div id="acms-content" class="sections column">
  <button class="button add" onclick="acms_content_add_section(this)">Add section</button>
  <button class="button update" onclick="acms_content_update()">Update</button>
</div>
<br />

<!-- <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" aria-hidden="true" focusable="false">
  <path d="M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z"></path>
</svg>

<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" aria-hidden="true" focusable="false">
  <path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
</svg> -->

<?php
}

?>