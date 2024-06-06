function acms_content_remove(container) {
  jQuery(function ($) {
    $(container).remove();
  });
}

function acms_content_add_section(button) {
  jQuery(function ($) {
    $(`
      <div class="section">
        <label>Section</label>
        <div class="row">
          <input class="widefat" type="text" placeholder="Section name" />
          <button class="button" onclick="acms_content_remove(this.parentNode.parentNode)">Remove section</button>
        </div>

        <div class="articles column">
          <button class="button add" onclick="acms_content_add_article(this)">Add article</button>
        </div>
      </div>
    `).insertBefore(button);
  });
}

function acms_content_add_article(button) {
  jQuery(function ($) {
    $(`
      <div class="article">
        <label>Article</label>
        <div class="row">
          <input class="widefat" type="text" placeholder="Article title" />
          <input class="page-num" type="number" placeholder="#" />
          <button class="button" onclick="acms_content_remove(this.parentNode.parentNode)">Remove article</button>
        </div>

        <div class="authors">
          <button class="button add" onclick="acms_content_add_author(this)">Add author</button>
        </div>
      </div>
    `).insertBefore(button);
  });
}

function acms_content_add_author(button) {
  jQuery(function ($) {
    $(`
      <div class="row">
        <input class="widefat" type="text" placeholder="Byline" />
        <button class="button" onclick="acms_content_remove(this.parentNode)">Remove</button>
      </div>
    `).insertBefore(button);
  });
}

function archivescms_update() {
  jQuery(function ($) {
    //
  });
}
