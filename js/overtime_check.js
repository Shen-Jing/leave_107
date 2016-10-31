var arr_dept;
$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/overtime_check_ajax.php',
            data: {
              oper: 'qry_year_and_dept'
            },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                var end_year = parseInt(JData.qry_year.END_YEAR[0]);
                $('#qry_year').append(row0);
                for (var i = end_year - 1; i <= end_year + 1; i++) {
                    var row = "<option value=" + i + ">" + i + "</option>";
                    $('#qry_year').append(row);
                }

                var row0 = "<option selected disabled class='text-hide'>請選擇單位</option>";
                var row_length = JData.qry_dept.DEPT_NO.length;
                $('#qry_dept').append(row0);
                for (var i = 0; i < row_length; i++) {
                    var row = "<option value=" + JData.qry_dept.DEPT_NO[i] + ">" + JData.qry_dept.DEPT_FULL_NAME[i] + "</option>";
                    $('#qry_dept').append(row);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {}
        });

        // 改變滑鼠游標樣式
        $('#container tbody').on('mouseover', 'tr', function() {
            this.style.cursor = 'pointer';
        });

        // 取得詳細刷卡記錄
        // $.ajax({
        //     url: '../view_card.php',
        //     data: {
        //         oper: 'qry_dept'
        //     },
        //     type: 'POST',
        //     dataType: "json",
        //     success: function(JData) {
        //         if (JData.error_code)
        //             toastr["error"](JData.error_message);
        //         else arr_dept = JData; //一開始就查詢系所資料放到陣列備用
        //     },
        //     error: function(xhr, ajaxOptions, thrownError) {}
        // });


    });




// function CRUD(oper, id) {
//     id = id || ''; //預設值
//     if (oper == 3)
//         if (!confirm("是否確定要刪除?")) return false;
//
//     $.ajax({
//         url: 'ajax/subject_ajax.php',
//         data: {
//             old_id: id,
//             id: $('#id' + id).val(),
//             name: $('#name' + id).val(),
//             rate: $('#rate' + id).val(),
//             section: $('#section' + id).val(),
//             compare: $('#compare' + id).val(),
//             qualified: $('#qualified' + id).val(),
//             dept_id: $('#qry_dept').val(),
//             oper: oper
//         },
//         type: 'POST',
//         dataType: "json",
//         success: function(JData) {
//             if (JData.error_code)
//                 toastr["error"](JData.error_message);
//             else {
//                 if (oper == "0") { //查詢
//                     var arr_qualified = ["無", "頂標", "前標", "均標", "後標", "底標", "70分"];
//                     $('#_content').empty();
//                     for (var i = 0; i < JData.ID.length; i++) {
//                         var row_qualified = "";
//                         for (var j = 0; j < 7; j++) {
//                             if (JData.QUALIFIED[i] == j)
//                                 row_qualified = row_qualified + "<option value='" + j + "' selected>" + arr_qualified[j] + "</option>";
//                             else
//                                 row_qualified = row_qualified + "<option value='" + j + "'>" + arr_qualified[j] + "</option>";
//                         }
//
//                         var row = "<tr>";
//                         row = row + "<td class='col-xs-2'><input value='" + JData.ID[i] + "'  name='id" + JData.ID[i] + "' id='id" + JData.ID[i] + "' type='text' class='form-control'></td>";
//                         row = row + "<td class='col-xs-1'><input value='" + JData.SECTION[i] + "' name='section" + JData.ID[i] + "' id='section" + JData.ID[i] + "' type='text' class='form-control'></td>";
//                         row = row + "<td><input value=" + JData.NAME[i] + " name='name" + JData.ID[i] + "' id='name" + JData.ID[i] + "' type='text' class='form-control'></td>";
//                         row = row + "<td class='col-xs-1'><input value='" + JData.RATE[i] + "' name='rate" + JData.ID[i] + "' id='rate" + JData.ID[i] + "' type='text' class='form-control'></td>";
//                         row = row + "<td class='col-xs-1'><select name='qualified" + JData.ID[i] + "' id='qualified" + JData.ID[i] + "' class='form-control'><option></option>" + row_qualified + "</select></td>";
//                         row = row + "<td class='col-xs-2'><input value='" + JData.COMPARE[i] + "' name='compare" + JData.ID[i] + "' id='compare" + JData.ID[i] + "' type='text' class='form-control'></td>";
//                         row = row + "<td><button type='button' class='btn-success' name='modify' id='modify' value=" + JData.ID[i] + "  onclick='CRUD(2," + JData.ID[i] + ")' title='修改儲存'> <i class='fa fa-save'></i> </button>";
//                         row = row + "    <button type='button' class='btn-danger' name='delete' id='delete' onclick='CRUD(3," + JData.ID[i] + ")' title='刪除'><i class='fa fa-times'></i></button></td>";
//                         row = row + "</tr>";
//                         $('#_content').append(row);
//                     }
//                     //新增列
//                     var row_qualified_new = "";
//                     for (var j = 0; j < 7; j++)
//                         row_qualified_new = row_qualified_new + "<option value='" + j + "'>" + arr_qualified[j] + "</option>";
//
//                     var row2 = "<tr class='info'>";
//                     row2 = row2 + "<td class='col-xs-2'><input value='' name='id' id='id' type='text' class='form-control'></td>";
//                     row2 = row2 + "<td class='col-xs-1'><input value='' name='section' id='section' type='text' class='form-control'></td>";
//                     row2 = row2 + "<td><input value='' name='name' id='name' type='text' class='form-control'></td>";
//                     row2 = row2 + "<td class='col-xs-1'><input value='' name='rate' id='rate' type='text' class='form-control'></td>";
//                     row2 = row2 + "<td class='col-xs-1'><select name='qualified' id='qualified' class='form-control'><option></option>" + row_qualified_new + "</select></td>";
//                     row2 = row2 + "<td class='col-xs-2'><input value='' name='compare' id='compare' type='text' class='form-control'></td>";
//                     row2 = row2 + "<td><button class='btn-primary' type='button' name='new' id = 'new' onclick='CRUD(1)' title='新增'> <i class='fa fa-plus'></i></button></td>";
//                     row2 = row2 + "</tr>";
//                     $('#_content').append(row2);
//                 } else if (oper == 1) { //新增
//                     toastr["success"]("資料新增成功!");
//                     CRUD(0); //reload
//                 } else if (oper == 2) { //修改
//                     toastr["success"]("資料修改成功!");
//                     CRUD(0); //reload
//                 } else if (oper == 3) { //刪除
//                     toastr["success"]("資料刪除成功!");
//                     CRUD(0); //reload
//                 }
//             }
//         },
//         beforeSend: function() {
//             $('#loading').show();
//         },
//         complete: function() {
//             $('#loading').hide();
//         },
//         error: function(xhr, ajaxOptions, thrownError) {}
//     });
// }
