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
      if (cEl.children('ol').length === 0)
      {
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
      }
      else
        usersInsert(cEl.children('ol').children('li'), cEl.parent().parent().attr('id'));
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
    }
  });
  $('#empl').change(function() {
    filter();
  });
  $('#filter').on('input', function() {
    filter();
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
  function usersInsert(users, grp)
  {
    for (var i = 0; i < users.length; i++)
      if (users.eq(i).has('ol').length === 1)
        usersInsert(users.eq(i).children('ol').children('li'), grp);
      else if (!users.eq(i).hasClass('disable'))
        $.ajax({
          url: 'ajax/grp_user_insert_ajax.php',
          type: 'POST',
          data: {grp: grp, user: users.eq(i).attr('id')},
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
  }
  function filter()
  {
    var input = $('#filter').val();
    var arr = $('#empl').val();

    $('.user li').hide();
    if (input != '' & arr.length != 0)
      for (var i = 0; i < arr.length; i++)
      {
        $('.user li[id*=' + input + '][name^=' + arr[i] + ']').parent().parent().parent().parent().show();
        $('.user li[id*=' + input + '][name^=' + arr[i] + ']').parent().parent().show();
        $('.user li[id*=' + input + '][name^=' + arr[i] + ']').show();
        $('.user li[class*=' + input + '][name^=' + arr[i] + ']').parent().parent().parent().parent().show();
        $('.user li[class*=' + input + '][name^=' + arr[i] + ']').parent().parent().show();
        $('.user li[class*=' + input + '][name^=' + arr[i] + ']').show();
      }
    else if (arr.length != 0)
      for (var i = 0; i < arr.length; i++)
      {
        $('.user li[name^=' + arr[i] + ']').parent().parent().parent().parent().show();
        $('.user li[name^=' + arr[i] + ']').parent().parent().show();
        $('.user li[name^=' + arr[i] + ']').show();
      }
    else if (input != '')
    {
      $('.user li[id*=' + input + ']').parent().parent().parent().parent().show();
      $('.user li[id*=' + input + ']').parent().parent().show();
      $('.user li[id*=' + input + ']').show();
      $('.user li[class*=' + input + ']').parent().parent().parent().parent().show();
      $('.user li[class*=' + input + ']').parent().parent().show();
      $('.user li[class*=' + input + ']').show();
    }
    else
      $('.user li').show();
  }
});
