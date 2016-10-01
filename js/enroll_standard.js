var arr_dept;
$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/enroll_standard_ajax.php',
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
                        url: 'ajax/enroll_standard_ajax.php',
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
                            CRUD(0);//query
                        },
                        error: function(xhr, ajaxOptions, thrownError) {}
                    });
                }
            }
        );

        $('#qry_dept').change( //選擇系所後
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0); //query
                }
            }
        );

        //取得所屬系所資料
        //var getData = function() {
        $.ajax({
            url: 'ajax/enroll_standard_ajax.php',
            data: {
                oper: 'qry_dept'
            },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                if (JData.error_code)
                    toastr["error"](JData.error_message);
                else arr_dept = JData; //一開始就查詢系所資料放到陣列備用                
            },
            error: function(xhr, ajaxOptions, thrownError) {}
        });
        //};
    });




function CRUD(oper, id) {
    id = id || ''; //預設值    
    $.ajax({
        url: 'ajax/enroll_standard_ajax.php',
        data: {
            old_id: id,
            id: $('#id' + id).val(),
            allperson: $('#allperson' + id).val(),
            enrollperson: $('#enrollperson' + id).val(),
            resultperson: $('#resultperson' + id).val(),
            resultscore: $('#resultscore' + id).val(),
            secondperson: $('#secondperson' + id).val(),
            secondscore: $('#secondscore' + id).val(),
            dept_id: $('#qry_dept').val(),
            campus_id: $('#qry_campus').val(),
            oper: oper
        },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else {
                if (oper == "0") { //查詢
                    $('#_content').empty();
                    for (var i = 0; i < JData.ID.length; i++) {
                        var row = "<tr>";
                        row = row + "<td class='col-xs-1'><input value='" + JData.ID[i] + "'  name='id" + JData.ID[i] + "' id='id" + JData.ID[i] + "' type='text' class='form-control' disabled='disabled'></td>";
                        row = row + "<td class='col-xs-2'>" + JData.DEPT_NAME[i] + "</td>";
                        row = row + "<td class='col-xs-1'>" + JData.ORGANIZE_NAME[i] + "</td>";
                        row = row + "<td class='col-xs-1'>" + JData.ORASTATUS_NAME[i] + "</td>";
                        row = row + "<td class='col-xs-1'><input value='" + JData.ALLPERSON[i] + "' name='allperson" + JData.ID[i] + "' id='allperson" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-1'><input value='" + JData.ENROLLPERSON[i] + "' name='enrollperson" + JData.ID[i] + "' id='enrollperson" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-1'><input value='" + JData.RESULTPERSON[i] + "' name='resultperson" + JData.ID[i] + "' id='resultperson" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-1'><input value='" + JData.RESULTSCORE[i] + "' name='resultscore" + JData.ID[i] + "' id='resultscore" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-1'><input value='" + JData.SECONDPERSON[i] + "' name='secondperson" + JData.ID[i] + "' id='secondperson" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-1'><input value='" + JData.SECONDSCORE[i] + "' name='secondscore" + JData.ID[i] + "' id='secondscore" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-1'><button type='button' class='btn-success' name='modify' id='modify' value=" + JData.ID[i] + "  onclick='CRUD(2," + JData.ID[i] + ")' title='修改儲存'> <i class='fa fa-save'></i> </button></td>";
                        row = row + "</tr>";
                        $('#_content').append(row);
                    }                
                } else if (oper == 2) { //修改
                    toastr["success"]("資料修改成功!");
                    CRUD(0); //reload
                } 
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
}
