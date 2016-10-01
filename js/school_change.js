$(function() {
    $("#school_sidebar").change(function(e) {
       // $('#campus')[0].innerHTML = "<option value=''>請選擇</option>";
        if ($(":selected", this).val() !== "") {
            $.ajax({
                url: "ajax/school_change_ajax.php",
                data: 'id=' + $(":selected", this).val() + "&type=1",
                type: "POST",
                dataType: "json",
                success: function(Jdata) {
                    //console.log('in');
                    //var data = JSON.parse(msg);
                    console.log(Jdata.title_name);
                    location.reload();
                    // for (var i = 0; i < data.length; i++) {
                    //     var option = $("<option value='" + data[i].id + "'>" + data[i].name + "</option>");
                    //     $('#campus').append(option);

                    // }
                    // $('#campus').material_select(); // 發動 materialize 效果
                    // $('#campus_field > .caret').remove(); // 清除多餘箭頭
                    // $('#department').material_select(); // 發動 materialize 效果
                    // $('#department_field > .caret').remove(); // 清除多餘箭頭
                },
                error: function(xhr, ajaxOptions, thrownError) {}
            });
        }
    });
});
