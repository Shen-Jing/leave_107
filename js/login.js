$( // 表示網頁完成後才會載入
    function() {
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        $("#form").submit(function(e) {            
            $.ajax({
                url: 'ajax/login_ajax.php',
                data: $('#form').serialize(),
                type: 'POST',
                dataType: "json",
                success: function(Jdata) {
                    if (Jdata.error_code == 0)
                        top.location.href = "index.php";
                    else {
                        //http://codeseven.github.io/toastr/demo.html                   
                        toastr["error"](Jdata.error_message);
                    }
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(xhr, ajaxOptions, thrownError) {}
            });
            e.preventDefault(); //STOP default action
            //e.unbind(); //unbind. to stop multiple form submit.
            //$("#loading").css("display", "none");
        });
    }
);
