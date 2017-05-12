$(function(){
  // 按下 "確定新增" 時觸發
  $('#add-btn').click(function(event) {
    // 防呆
    if ($('#' + $('#add-id').val()).length > 0)
    {
      toastr['warning']('該 id 目前已被使用！');
      return;
    }
    // 將表單欄位值送去 ajax
    $.ajax({
      url: 'ajax/grp_node_ajax.php',
      type: 'POST',
      data: {oper: 1, id: $('#add-id').val(), name: $('#add-name').val()},
      success: function(result){
        toastr["success"]("成功~~");
        /*
        var text = '\
        <li id="' + $('#add-id').val() + '">\
          <span class="left">' + $('#add-name').val() + '</span>\
          <span class="right">\
            <button class="edit-opener btn btn-info btn-xs">編輯</button> <button class="delete btn btn-danger btn-xs">刪除</button>\
          </span>\
        </li>';
        $('.root').append(text);
        */
       // 新增若成功就重新整理
        location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        toastr["error"]("失敗 QAQ");
        // 失敗輸出錯誤訊息
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    });
    // 關閉新增視窗
    $("#modal-add").modal("hide");
  });
  // 按下 "確定編輯" 時觸發
  $('#edit-btn').click(function(event) {
    // 將表單欄位值送去 ajax
    $.ajax({
      url: 'ajax/grp_node_ajax.php',
      type: 'POST',
      data: {oper: 2, id: $('#edit-id').val(), name: $('#edit-name').val()},
      success: function(result){
        toastr["success"]("成功~~");
        // 將 name 改成新的
        $('#' + $('#edit-id').val()).children('.left').html($('#edit-name').val());
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        toastr["error"]("失敗 QAQ");
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    });
    // 關閉編輯視窗
    $("#modal-edit").modal("hide");
  });
  $(".add-opener").click(function() {
    $("#modal-add").modal("show");
  });
  $(".edit-opener").click(function() {
    // 改視窗標題、id 值以及 name 值
    $("#modal-edit .modal-title").html($(this).parent().parent().attr('id') + " - 編輯群組");
    $("#edit-id").val($(this).parent().parent().attr('id'));
    $("#edit-name").val($(this).parent().prev().text());
    $("#modal-edit").modal("show");
  });
  // 按下 "刪除" 時觸發
  $(".delete").click(function(event) {
    var id = $(this).parent().parent().attr('id');
    $.ajax({
      url: 'ajax/grp_node_ajax.php',
      type: 'POST',
      data: {oper: 3, id: id, name: ''},
      success: function(result){
        toastr["success"]("成功~~");
        // 將自己移除掉
        $('#' + id).remove();
      },
      error: function(jqXHR, textStatus, errorThrown)
      {
        toastr["error"]("失敗 QAQ");
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
    });
  });
});
