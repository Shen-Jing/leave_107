$(function(){
  var group = $("#left > .root").sortable({
    group: 'pgm',
    drag: false,
    onMousedown: function(cEl, _super, e){
      if (cEl.attr('class') === 'disable')
      {
        toastr['warning']('該節點目前不可拖動！');
        return false;
      }
      else
        return true;
    },
    onDragStart: function ($item, container, _super) {
      // Duplicate items of the no drop area
      $item.clone().insertAfter($item);
      _super($item, container);
    },
    onDrop: function (cEl, container, _super) {
      var file = cEl.attr('id');
      $.ajax({
        url: 'ajax/temp_insert_ajax.php',
        type: 'POST',
        data: {file: file},
        success: function(result){
          toastr["success"]("成功~~");
          console.log(result);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          toastr["error"]("失敗 QAQ");
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
      });
      _super(cEl, container);
    }
  });
  $(".pgm").sortable({
    group: 'pgm',
    drop: false
  });

  $(".delete").click(function(event) {
    var file = $(this).parent().parent().attr('id');
    $.ajax({
      url: 'ajax/temp_delete_ajax.php',
      type: 'POST',
      data: {file: file},
      success: function(result){
        toastr["success"]("成功~~");
        console.log(result);
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        toastr["error"]("失敗 QAQ");
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    });
    $(this).parent().parent().remove();
  });
  $('#filter').on('input', function() {
    var input = $(this).val();
    if (input != '')
    {
      $('.pgm li').hide();
      $('.pgm li[id*=' + input + ']').parent().parent().show();
      $('.pgm li[id*=' + input + ']').show();
      $('.pgm li[class*=' + input + ']').parent().parent().show();
      $('.pgm li[class*=' + input + ']').show();
    }
    else
      $('.pgm li').show();
  });
  $('li').has('ol').children('i').click(function() {
    $(this).siblings('ol').slideToggle();
    if ($(this).hasClass('fa-caret-square-o-up'))
    {
      $(this).removeClass('fa-caret-square-o-up');
      $(this).addClass('fa-caret-square-o-down');
    }
    else {
      $(this).removeClass('fa-caret-square-o-down');
      $(this).addClass('fa-caret-square-o-up');
    }
  });
  for (var i = 0; i < $('#left > .root').children().length; i++)
  {
    var id = $('#left > .root').children().eq(i).attr('id');
    $('#right > .root #' + id).addClass('disable');
  }
});
