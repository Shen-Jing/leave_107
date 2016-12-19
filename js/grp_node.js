$(function(){
  $('#add-btn').click(function(event) {
    if ($('#' + $('#add-id').val()).length > 0)
    {
      toastr['warning']('該 id 目前已被使用！');
      return;
    }
    $.ajax({
      url: 'ajax/grp_insert_ajax.php',
      type: 'POST',
      data: {id: $('#add-id').val(), name: $('#add-name').val()},
      success: function(result){
        toastr["success"]("成功~~");
        console.log(result);
        var text = '<li id="' + $('#add-id').val() + '">' + $('#add-name').val() + '\
        <ol class="grp">\
        </ol>\
      </li>';
        $('.root').append(text);
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        toastr["error"]("失敗 QAQ");
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        var text = '<li id="' + $('#add-id').val() + '">' + $('#add-name').val() + '\
        <ol class="grp">\
          <li class="pgm-li">pgm\
            <ol class="pgm"></ol>\
          </li>\
          <li class="usr-li">user\
            <ol class="usr"></ol>\
          </li>\
        </ol>\
      </li>';
        $('.root').append(text);
      }
    });
    $("#modal-add").modal("hide");
  });
  $('#edit-btn').click(function(event) {
    $.ajax({
      url: 'ajax/grp_update_ajax.php',
      type: 'POST',
      data: {id: $('#edit-id').val(), name: $('#edit-name').val()},
      success: function(result){
        toastr["success"]("成功~~");
        console.log(result);
        $('#' + $('#edit-id').val()).html($('#edit-name').val() + "<span class='right'><button class='edit-opener btn btn-info btn-xs'>編輯</button></span>");
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        toastr["error"]("失敗 QAQ");
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        $('#' + $('#edit-id').val() + ' > span:not(.right)').html("<i class='fa " + $('#edit-img').val() + " fa-fw'></i> " + $('#edit-name').val());
      }
    });
    $("#modal-edit").modal("hide");
  });
  $(".add-opener").click(function() {
    $("#modal-add").modal("show");
  });
  var edit_event = function() {
    $("#modal-edit .modal-title").html($(this).parent().parent().attr('id') + " - 編輯群組");
    $("#edit-id").val($(this).parent().parent().attr('id'));
    $("#edit-name").val($(this).parent().prev().text().slice(1));
    //toastr['warning']($("#edit-name").val($(this).parent().prev().text().slice(1)));
    $("#modal-edit").modal("show");
  };
  $(".edit-opener").click(edit_event);

  $(".delete").click(function(event) {
    var id = $(this).parent().parent().attr('id');
    $.ajax({
      url: 'ajax/grp_delete_ajax.php',
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
