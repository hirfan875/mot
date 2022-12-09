"use strict";

$("ul.dd_list").sortable({
  items: 'li',
  cursor: 'pointer',
  nested: 'ul',
  tolerance: 'intersect',
  placeholder: 'placeholder',
  update: function (e, ui) {
    var sortedRows = [];
    $('.dd_list > li').each(function (index, element) {
      sortedRows.push({ id: $(this).data("id"), order: index });
    });

    $(".dd").prepend('<div class="alert alert-success ui-success">Order updated</div>');
    setTimeout(function () {
      $('.ui-success').remove();
    }, 2000);

    // send request to server
    axios.post(sorting_post_url, {
      items: sortedRows
    });
  }
});