$(function() {
    $("#execute").click(function() {
        if (!confirm("是否確定要執行第二次成績處理作業?")) return false;
        $("#execute").hide();
        $.ajax({
            url: 'ajax/process_grade_nd_ajax.php',
            data: '',
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                if (JData.error_code)
                    message(JData.error_message, "danger", 5000);
                else
                    toastr["success"]("第二次成績處理作業完成!");
                $("#execute").show();

            },
            beforeSend: function() {
                $('#loading').show();
            },
            complete: function() {
                $('#loading').hide();
            },
            error: function(xhr, ajaxOptions, thrownError) {}
        });
    });
});
