var arr_dept;
$( // 表示網頁完成後才會載入
    function() {
        $("#classroom_s").focus();

        $("#btn-create").click(function() {
            if ($("#classroom_s").val() == ""){
                alert("請輸入試場起號!");
                $("#classroom_s").focus();
                return false;
            }
            if ($("#classroom_e").val() == ""){
                alert("請輸入試場訖號!")
                $("#classroom_e").focus();
                return false;
            }
            $('#form1').attr("action", "rpt/desk_name_list.php")
                .attr("method", "post").attr("target", "_blank");
            $('#oper').val('classroom');
            $('#form1').submit();
        });

        $("#btn-spare").click(function() {
            $('#form1').attr("action", "rpt/desk_name_list.php")
                .attr("method", "post").attr("target", "_blank");
            $('#oper').val('spare');
            $('#form1').submit();
        });
    });
