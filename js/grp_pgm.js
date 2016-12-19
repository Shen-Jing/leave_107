$(function(){
  var group = $(".grp").sortable({
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
      var pgm = cEl.attr('id');
      var grp = cEl.parent().parent().attr('id');
      for (var flag = 0; pgm.length > 0; pgm = pgm.slice(0, -2))
      {
        toastr['warning']($('#left > .root > #admin > .grp').has('#' + pgm).length);
        if (flag == 1 && $('#left > .root > #admin > .grp').has('#' + pgm).length > 0)
          break;
        $.ajax({
          url: 'ajax/grp_pgm_insert_ajax.php',
          type: 'POST',
          data: {grp: grp, pgm: pgm},
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
        flag = 1;
      }
      _super(cEl, container);
    }
  });
  $(".pgm").sortable({
    group: 'pgm',
    drop: false
  });

  $(".delete").click(function(event) {
    var pgm = $(this).parent().parent().attr('id');
    var grp = $(this).parent().parent().parent().parent().attr('id');
    $.ajax({
      url: 'ajax/grp_pgm_delete_ajax.php',
      type: 'POST',
      data: {grp: grp, pgm: pgm},
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
    }
  });
});
