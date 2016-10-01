var arr_dept;
$( // 表示網頁完成後才會載入
    function() {
        $.ajax({
            url: 'ajax/pangform_choose_ajax.php',
            data: { oper: 'qry_campus' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇學院</option>";
                $('#qry_campus').append(row0);
                for (var i = 0; i < JData.ID.length; i++) {
                    var row = "<option value=" + JData.ID[i] + ">" + JData.NAME[i] + "</option>";
                    $('#qry_campus').append(row);
                }
                //CRUD(0);//query
            },
            error: function(xhr, ajaxOptions, thrownError) {}
        });


        $('#qry_campus').change( //選擇學院後
            function(e) {
                if ($(':selected', this).val() !== '') {
                    $.ajax({
                        url: 'ajax/pangform_choose_ajax.php',
                        data: {
                            oper: 'qry_dept',
                            campus_id: $('#qry_campus').val()
                        },
                        type: 'POST',
                        dataType: "json",
                        success: function(JData) {
                            $('#qry_dept').empty();
                            var row0 = "<option selected disabled class='text-hide'>請選擇系所</option>";
                            $('#qry_dept').append(row0);
                            for (var i = 0; i < JData.ID.length; i++) {
                                var row = "<option value=" + JData.ID[i] + ">" + JData.NAME[i] + "</option>";
                                $('#qry_dept').append(row);
                            }
                            //CRUD(0);//query
                        },
                        error: function(xhr, ajaxOptions, thrownError) {}
                    });
                }
            }
        );

        $('#qry_dept').change( //選擇系所後
            function(e) {
                if ($(':selected', this).val() !== '') {
                    //$("#btn-report1").removeAttr("disabled");
                    //$("#btn-report2").removeAttr("disabled");
                }
            }
        );


        $("#btn-report").click(function() {
            $('#form1').attr("action", "rpt/pangform_list.php")
                .attr("method", "post").attr("target","_blank");
            $('#form1').submit();
        });
        
    });
