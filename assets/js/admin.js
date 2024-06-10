var acms_content_update_timeout = null;
var acms_content_override_timeout = null;

function acms_content_remove(container) {
  jQuery(function ($) {
    $(container).remove();

    acms_content_update();
  });
}

function acms_content_add_section(button) {
  jQuery(function ($) {
    $(`
      <div class="section">
        <label>Section</label>
        <div class="row">
          <input class="widefat" type="text" name="section-name" placeholder="Section name" oninput="acms_content_queue_update()" />
          <button class="button" onclick="acms_content_remove(this.parentNode.parentNode)">Remove section</button>
        </div>

        <div class="articles column">
          <button class="button add" onclick="acms_content_add_article(this)">Add article</button>
        </div>
      </div>
    `).insertBefore(button);

    acms_content_queue_update();
  });
}

function acms_content_add_article(button) {
  jQuery(function ($) {
    $(`
      <div class="article">
        <label>Article</label>
        <div class="row">
          <input class="widefat" type="text" name="article-title" placeholder="Article title" oninput="acms_content_queue_update()" />
          <input class="page-num" type="number" name="article-page-num" placeholder="#" oninput="acms_content_queue_update()" />
          <button class="button" onclick="acms_content_remove(this.parentNode.parentNode)">Remove article</button>
        </div>

        <div class="authors">
          <button class="button add" onclick="acms_content_add_author(this)">Add author</button>
        </div>
      </div>
    `).insertBefore(button);

    acms_content_queue_update();
  });
}

function acms_content_add_author(button) {
  jQuery(function ($) {
    $(`
      <div class="row">
        <input class="widefat" type="text" name="byline" placeholder="Byline" oninput="acms_content_queue_update()" />
        <button class="button" onclick="acms_content_remove(this.parentNode)">Remove</button>
      </div>
    `).insertBefore(button);

    acms_content_queue_update();
  });
}

function acms_content_update() {
  jQuery(function ($) {
    let json_textarea = $("textarea#article_content[name=article_content]");
    let data = [];

    $("#acms-content.sections .section").each(function (i) {
      let name = $(this).find("input[name=section-name]").val();
      let articles = [];

      $(this)
        .find(".articles .article")
        .each(function (j) {
          let title = $(this).find("input[name=article-title]").val();
          let pageNum = parseInt(
            $(this).find("input[name=article-page-num]").val()
          );
          let bylines = [];

          $(this)
            .find(".authors .row")
            .each(function (k) {
              let byline = $(this).find("input[name=byline]").val();
              bylines.push(byline);
            });

          articles.push({
            page: pageNum,
            title: title,
            bylines: bylines,
          });
        });

      data.push({
        name: name,
        articles: articles,
      });
    });

    json_textarea.val(JSON.stringify(data));
  });
}

function acms_content_queue_update() {
  if (acms_content_update_timeout != null)
    clearTimeout(acms_content_update_timeout);
  acms_content_update_timeout = setTimeout(acms_content_update, 3000);
}

function acms_content_override() {
  jQuery(function ($) {
    //
  });
}

function acms_content_queue_override() {
  if (acms_content_override_timeout != null)
    clearTimeout(acms_content_override_timeout);
  acms_content_override_timeout = setTimeout(acms_content_override, 3000);
}

/* contributors */
var acms_contribs_update_timeout = null;
var acms_contribs_override_timeout = null;

function acms_contribs_remove(container) {
  jQuery(function ($) {
    $(container).remove();

    acms_contribs_update();
  });
}

function acms_contribs_add_group(button) {
  jQuery(function ($) {
    $(`
      <div class="group">
        <label>Group</label>
        <div class="row">
          <input class="widefat" type="text" name="group-name" placeholder="Group name" oninput="acms_contribs_queue_update()" />
          <button class="button" onclick="acms_content_remove(this.parentNode.parentNode)">Remove group</button>
        </div>

        <div class="people column">
          <button class="button add" onclick="acms_contribs_add_person(this)">Add contributor</button>
        </div>
      </div>
    `).insertBefore(button);
  });
}

function acms_contribs_add_person(button) {
  jQuery(function ($) {
    $(`
      <div class="row">
        <input class="widefat" type="text" name="byline" placeholder="Byline" oninput="acms_contribs_queue_update()" />
        <input class="widefat" type="text" name="title" placeholder="Title (optional)" oninput="acms_contribs_queue_update()" />
        <button class="button" onclick="acms_contribs_remove(this.parentNode)">Remove</button>
      </div>
    `).insertBefore(button);
  });
}

function acms_contribs_update() {
  jQuery(function ($) {
    let json_textarea = $("textarea#contribs[name=contribs]");
    let data = [];

    $("#acms-contribs.groups .group").each(function (i) {
      let name = $(this).find("input[name=group-name]").val();
      let people = [];

      $(this)
        .find(".people .row")
        .each(function (j) {
          let byline = $(this).find("input[name=byline]").val();
          let title = $(this).find("input[name=title]").val();
          people.push({
            byline: byline,
            title: title,
          });
        });

      data.push({
        name: name,
        people: people,
      });
    });

    json_textarea.val(JSON.stringify(data));
  });
}

function acms_contribs_queue_update() {
  if (acms_contribs_update_timeout != null)
    clearTimeout(acms_contribs_update_timeout);
  acms_contribs_update_timeout = setTimeout(acms_contribs_update, 3000);
}

function acms_contribs_override() {
  jQuery(function ($) {
    //
  });
}

function acms_contribs_queue_override() {
  if (acms_contribs_override_timeout != null)
    clearTimeout(acms_contribs_override_timeout);
  acms_contribs_override_timeout = setTimeout(acms_contribs_override, 3000);
}
