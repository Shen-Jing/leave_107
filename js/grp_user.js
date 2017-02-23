$(function(){
  var group = $(".grp").sortable({
    group: 'user',
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
      var user = cEl.attr('id');
      var grp = cEl.parent().parent().attr('id');
      $.ajax({
        url: 'ajax/grp_user_insert_ajax.php',
        type: 'POST',
        data: {grp: grp, user: user},
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
  $(".user").sortable({
    group: 'user',
    drop: false
  });

  $(".delete").click(function(event) {
    var user = $(this).parent().parent().attr('id');
    var grp = $(this).parent().parent().parent().parent().attr('id');
    $.ajax({
      url: 'ajax/grp_user_delete_ajax.php',
      type: 'POST',
      data: {grp: grp, user: user},
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
  $('#grp').change(function(){
    $('#right > ol li').removeClass('disable');
	  var grpid = $(this).val();
    if (grpid === 'all-display')
    {
    	$('.grp-li').show();
      $('#right > ol li').addClass('disable');
    }
    else
    {
    	$('#' + grpid).show().siblings().hide();
      for (var i = 0; i < $('#' + grpid).children('ol').children().length; i++)
      {
        var id = $('#' + grpid).children('ol').children().eq(i).attr('id');
        $('#right > ol #' + id).addClass('disable');
      }
      $('#right > ol > li').addClass('disable');
    }
  });
  $('#filter').on('input', function() {
    var input = $(this).val();
    if (input != '')
    {
      $('.user li').hide();
      $('.user li[id^=' + input + ']').parent().parent().show();
      $('.user li[id^=' + input + ']').show();
    }
    else
      $('.user li').show();
  });
  $('li').has('ol').click(function() {
    $(this).children('ol').slideToggle();
  });
});
