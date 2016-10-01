$( // 表示網頁完成後才會載入
    function() {
        //產生准考證
        $("#btn-generate").click(function() {
            if (!confirm("是否確定要執行產生准考證作業?")) return false;
            $("#btn-generate").hide();
            $.ajax({
                url: 'ajax/ticket_generate_ajax.php',
                data: { oper: 'oper' },
                type: 'POST',
                dataType: 'json',
                success: function(JData) {
                    if (JData.error_code)
                        message(JData.error_message, "danger", 5000);
                    else 
                        message(JData.error_message, "success", 50000);
                        //toastr["success"]("產生准考證作業完成!");
                    $("#btn-generate").show();
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

    }
);
