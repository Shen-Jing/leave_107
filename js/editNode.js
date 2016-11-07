$(function(){
  var group = $(".root").sortable({
    group: 'nested',
    onMousedown: function(cEl, _super, e){
      if (cEl.children('ol').children('li').length)
      {
        toastr['warning']('父節點不可被移動');
        return false;
      }
      else
        return true;
    },
    onDrop: function (cEl, container, _super) {
      var o_id = cEl.attr('id');
      var n_id = cEl.parent().parent().attr('id');
      if (n_id === undefined)
        n_id = String.fromCharCode($('.root > li').index(cEl) + 65);
      else {
        var index = $('#' + n_id + ' > ol > li').index(cEl) + 1;
        if (index < 10)
          n_id = n_id + '0' + index;
        else
          n_id = n_id + index;
      }
      $.ajax({
        url: 'ajax/updateNode.php',
        type: 'POST',
        data: {o_id: o_id, n_id: n_id},
        success: function(result){
          toastr["success"]("成功~~");
          console.log(result);
          cEl.attr({
            id: n_id
          });
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
  /*
  var group = $(".root").sortable({
    items: 'li',
    placeholder: 'placeholder',
    revert: 200,
    cursor: 'move',
    onMousedown: function(cEl, _super, e){
      if (cEl.children('ol').children('li').length)
      {
        toastr['warning']('父節點不可被移動');
        return false;
      }
      else
        return true;
    },
    onDrop: function (cEl, container, _super) {
      var o_id = cEl.attr('id');
      var n_id = cEl.parent().parent().attr('id');
      var index = $('#' + n_id + ' > ol > li').index(cEl) + 1;
      if (index < 10)
        n_id = n_id + '0' + index;
      else
        n_id = n_id + index;
      $.ajax({
        url: '/ajax/updateNode.php',
        type: 'POST',
        data: {o_id: o_id, n_id: n_id},
        success: function(result){
          toastr["success"]("成功~~");
          console.log(result);
          cEl.attr({
            id: n_id
          });
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
          toastr["error"]("失敗 QAQ");
          console.log(JSON.stringify(jqXHR));
          console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
      });
    },
    onDragStart: function(cEl, container, _super, event){
      $('.placeholder').height(cEl.height());
    }
  });
  */

  $('#add-btn').click(function(event) {
    $type = '';
    if ($('#add-type').prop("checked"))
      $type = '1';
    //$.post('../ajax/insertNode.php', {id: $('#id').val(), name: $('#name').val(), url: $('#url').val(), type: $('#type').val(), img: $('#img').val()}, function(result){alert(result)});
    $.ajax({
      url: 'ajax/insertNode.php',
      type: 'POST',
      data: {id: $('#add-id').val(), name: $('#add-name').val(), url: $('#add-url').val(), type: $type, img: $('#add-img').val()},
      success: function(result){
        toastr["success"]("成功~~");
        console.log(result);
        if ($('#add-id').val().length > 2)
          $('#' + $('#add-id').val().slice(0, -2) + ' > ol').append("<li id='" + $('#add-id').val() + "'>\r\n<span>" + $('#add-name').val() + "</span><span class='right'><button class='edit-opener btn btn-info btn-sm'>編輯</button> <button class='delete btn btn-danger btn-sm'>刪除</button></span>");
        else
          $('.root').append("<li id='" + $('#add-id').val() + "'>\r\n<span>" + $('#add-name').val() + "</span><span class='right'><button class='edit-opener btn btn-info btn-sm'>編輯</button> <button class='delete btn btn-danger btn-sm'>刪除</button></span>");
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        toastr["error"]("失敗 QAQ");
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        if ($('#add-id').val().length > 2)
          $('#' + $('#add-id').val().slice(0, -2) + ' > ol').append("<li id='" + $('#add-id').val() + "'>\r\n<span>" + $('#add-name').val() + "</span><span class='right'><button class='edit-opener btn btn-info btn-sm'>編輯</button> <button class='delete btn btn-danger btn-sm'>刪除</button></span>");
        else
          $('.root').append("<li id='" + $('#add-id').val() + "'>\r\n<span>" + $('#add-name').val() + "</span><span class='right'><button class='edit-opener btn btn-info btn-sm'>編輯</button> <button class='delete btn btn-danger btn-sm'>刪除</button></span>");
      }
    });
    $("#modal-add").modal("hide");
  });

  $('#edit-btn').click(function(event) {
    $type = '';
    if ($('#edit-type').prop("checked"))
      $type = '1';
    $.ajax({
      url: 'ajax/updateNode.php',
      type: 'POST',
      data: {id: $('#edit-id').val(), name: $('#edit-name').val(), url: $('#edit-url').val(), type: $type, img: $('#edit-img').val()},
      success: function(result){
        toastr["success"]("成功~~");
        console.log(result);
        $('#' + $('#edit-id').val() + ' > span:not(.right)').html($('#edit-name').val());
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        toastr["error"]("失敗 QAQ");
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        $('#' + $('#edit-id').val() + ' > span:not(.right)').html($('#edit-name').val());
      }
    });
    $("#modal-edit").modal("hide");
  });

  $("#add-opener").click(function() {
    $("#modal-add").modal("show");
  });

  $(".edit-opener").click(function() {
    $("#modal-edit .modal-title").html($(this).parent().parent().attr('id') + " - 編輯節點");
    $("#edit-id").val($(this).parent().parent().attr('id'));
    $("#modal-edit").modal("show");
  });

  $(".delete").click(function(event) {
    var id = $(this).parent().parent().attr('id');
    $.ajax({
      url: 'ajax/deleteNode.php',
      type: 'POST',
      data: {id: id},
      success: function(result){
        toastr["success"]("成功~~");
        console.log(result);
        $('#' + id).remove();
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        toastr["error"]("失敗 QAQ");
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        $('#' + id).remove();
      }
    });
  });
});
