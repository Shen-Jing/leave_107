var arr_dept;
$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/department_ajax.php',
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

        //取得所屬系所資料
        //var getData = function() {
        $.ajax({
            url: 'ajax/department_ajax.php',
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

        $('#qry_campus').change( //選擇學院後
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0); //query
                }
            }
        );

        $("#btn-saveall").click(function() {
            if (!confirm("是否確定要全部儲存?")) return false;

            $.ajax({
                url: 'ajax/department_ajax.php',
                data: $("#form1").serialize() + "&oper=2&old_id=all",
                type: 'POST',
                dataType: "json",
                success: function(JData) {
                    if (JData.error_code)
                    //message(JData.error_message, "danger", 5000);
                        toastr["error"](JData.error_message);
                    else {
                        toastr["success"]("資料存檔成功!");
                        CRUD(0); //reload
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
        })

    }
);


function CRUD(oper, id) {
    id = id || ''; //預設值
    if (oper == 3)
        if (!confirm("是否確定要刪除?")) return false;

    $.ajax({
        url: 'ajax/department_ajax.php',
        data: $("#form1").serialize() + "&oper=" + oper + "&old_id=" + id,
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else {
                if (oper == "0") { //查詢
                    $('#_content').empty();
                    for (var i = 0; i < JData.ID.length; i++) {
                        var arr_test_type = ["", "", ""]; //0:不分組 1:分組 2:不分組選考 
                        arr_test_type[JData.TEST_TYPE[i]] = " selected";
                        var row_part = ""; //選考節次
                        for (var j = 0; j < 6; j++) {
                            if (JData.PART[i].substr(j, 1) == 1)
                                row_part = row_part + "<input name='part" + JData.ID[i] + "[]' type='checkbox' value='" + (j + 1) + "' checked>";
                            else
                                row_part = row_part + "<input name='part" + JData.ID[i] + "[]' type='checkbox' value='" + (j + 1) + "'>";
                        }
                        var row_flag_of_status = ""; //身分別
                        for (var j = 0; j < 3; j++) {
                            if (JData.FLAG_OF_STATUS[i].substr(j, 1) == 1)
                                row_flag_of_status = row_flag_of_status + "<input name='flag_of_status" + JData.ID[i] + "[]' type='checkbox' value='" + (j + 1) + "' checked>";
                            else
                                row_flag_of_status = row_flag_of_status + "<input name='flag_of_status" + JData.ID[i] + "[]' type='checkbox' value='" + (j + 1) + "'>";
                        }
                        var row_dept = ""; //歸屬系所 
                        for (var j = 0; j < arr_dept.DEPT_NO.length; j++) {
                            if (arr_dept.DEPT_NO[j] == JData.PRIME[i])
                                row_dept = row_dept + "<option value='" + arr_dept.DEPT_NO[j] + "' selected>" + arr_dept.DEPT_FULL_NAME[j] + "</option>";
                            else
                                row_dept = row_dept + "<option value='" + arr_dept.DEPT_NO[j] + "'>" + arr_dept.DEPT_FULL_NAME[j] + "</option>";
                        }
                        var arr_location = ["", ""]; //0:進德 1:寶山
                        arr_location[JData.LOCATION[i]] = " selected";

                        var row = "<tr>";
                        row = row + "<td class='col-xs-1'><input value=" + JData.ID[i] + "  name='id" + JData.ID[i] + "' id='id" + JData.ID[i] + "' type='text' class='form-control' maxlength='3'></td>";
                        row = row + "<td class='col-xs-3'><input value=" + JData.NAME[i] + " name='name" + JData.ID[i] + "' id='name" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-2'><select name='test_type" + JData.ID[i] + "' id='test_type" + JData.ID[i] + "' class='form-control'><option></option><option value='0' " + arr_test_type[0] + ">不分組</option><option value='1' " + arr_test_type[1] + ">分組</option><option value='2' " + arr_test_type[2] + ">不分組選考</option></select></td>";
                        row = row + "<td class='col-xs-1'><input value=" + JData.DEVIDE[i] + " name='devide" + JData.ID[i] + "' id='devide" + JData.ID[i] + "' type='text' class='form-control'></td>";
                        row = row + "<td class='col-xs-1'>" + row_flag_of_status + "</td>";
                        row = row + "<td class='col-xs-1'>" + row_part + "</td>";
                        row = row + "<td class='col-xs-1'><select name='prime" + JData.ID[i] + "' id='prime" + JData.ID[i] + "' class='form-control'><option></option>" + row_dept + "</select></td>";
                        row = row + "<td class='col-xs-1'><select name='location" + JData.ID[i] + "' id='location" + JData.ID[i] + "' class='form-control'><option></option><option value='0' " + arr_location[0] + ">進德</option><option value='1' " + arr_location[1] + ">寶山</option></select></td>";
                        row = row + "<td class='col-xs-1'><button type='button' class='btn-success' name='modify' id='modify' value=" + JData.ID[i] + "  onclick='CRUD(2," + JData.ID[i] + ")'  title='修改儲存'> <i class='fa fa-save'></i> </button>";
                        row = row + "    <button type='button' class='btn-danger' name='delete' id='delete' onclick='CRUD(3," + JData.ID[i] + ")' title='刪除'><i class='fa fa-times'></i></button></td>";
                        row = row + "</tr>";
                        $('#_content').append(row);
                    }
                    //新增列
                    var row_part_new = "";
                    for (var j = 0; j < 6; j++)
                        row_part_new = row_part_new + "<input name='part[]' type='checkbox' value='" + (j + 1) + "'>";

                    var row_flag_of_status_new = "";
                    for (var j = 0; j < 3; j++)
                        row_flag_of_status_new = row_flag_of_status_new + "<input name='flag_of_status[]' type='checkbox' value='" + (j + 1) + "'>";

                    var row_dept_new = "";
                    for (var j = 0; j < arr_dept.DEPT_NO.length; j++)
                        row_dept_new = row_dept_new + "<option value='" + arr_dept.DEPT_NO[j] + "'>" + arr_dept.DEPT_FULL_NAME[j] + "</option>";

                    var row2 = "<tr class='info'>";
                    row2 = row2 + "<td class='col-xs-1'><input value='' name='id' id='id' type='text' class='form-control' maxlength='3'></td>";
                    row2 = row2 + "<td class='col-xs-3'><input value='' name='name' id='name' type='text' class='form-control'></td>";
                    row2 = row2 + "<td class='col-xs-2'><select name='test_type' id='test_type' class='form-control'><option></option><option value='0'>不分組</option><option value='1'>分組</option><option value='2'>不分組選考</option></select></td>";
                    row2 = row2 + "<td class='col-xs-1'><input value='' name='devide' id='devide' type='text' class='form-control'></td>";
                    row2 = row2 + "<td class='col-xs-1'>" + row_flag_of_status_new + "</td>";
                    row2 = row2 + "<td class='col-xs-1'>" + row_part_new + "</td>";
                    row2 = row2 + "<td class='col-xs-1'><select name='prime' id='prime' class='form-control'><option></option>" + row_dept_new + "</select></td>";
                    row2 = row2 + "<td class='col-xs-1'><select name='location' id='location' class='form-control'><option></option><option value='0'>進德</option><option value='1'>寶山</option></select></td>";
                    row2 = row2 + "<td class='col-xs-1'><button class='btn-primary' type='button' name='new' id = 'new' onclick='CRUD(1)' title='新增'> <i class='fa fa-plus'></i></button></td>";
                    row2 = row2 + "</tr>";
                    $('#_content').append(row2);
                } else if (oper == 1) { //新增
                    toastr["success"]("資料新增成功!");
                    CRUD(0); //reload
                } else if (oper == 2) { //修改
                    toastr["success"]("資料修改成功!");
                    CRUD(0); //reload
                } else if (oper == 3) { //刪除
                    toastr["success"]("資料刪除成功!");
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
