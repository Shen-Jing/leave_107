$( // 表示網頁完成後才會載入
    function p_menu_onChange(){

        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/overtime_query_ajax.php',
            data: { oper: 'qry_year' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $('#qry_year').append(row0);
                for (var i = 99; i <= parseInt( JData.YEAR ) ; i++) {
                    if (i == parseInt( JData.YEAR ))
                        var row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        var row = "<option value=" +i+ ">" + i + " </option>";
                    $('#qry_year').append(row);
                }
                CRUD(0);//首次進入頁面query
            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });


        $('#qry_year').change( // 抓取區域選完的資料
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0); //query
                }
            }
        );
    }
);

function CRUD(oper, id) {
    id = id || ''; //預設值
    var yycho ;
    var yyval;
    yycho =document.form1.p_menu.selectedIndex;
    yyval=document.form1.p_menu.options[yycho].value;

    if (oper == 3)
        if (!confirm("是否確定要刪除?")) return false;

    $.ajax({
        url: 'ajax/overtime_query_ajax.php',
        data: { oper: oper , p_year: yyval, old_id: id },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else{
                if (oper == "0") { //查詢

                    $('#_content').empty();

                    if (JData.COUNT==0){
                        var row_part_new = "<center style='color:red'>您目前無任何加班記錄。</center><br>";
                        $('#_content').append(row_part_new);
                    }

                    else{
                            var row0 ="";
                            row0 = row0 + "<table class='table table-bordered col-md-8'><tbody><tr>";
                            row0 = row0 + "<td align=\"center\">加班日期</td>";
                            row0 = row0 + "<td align=\"center\">加班起始時間</td>";
                            row0 = row0 + "<td align=\"center\">加班結束時間</td>";
                            row0 = row0 + "<td align=\"center\">目前剩餘時數</td>";
                            row0 = row0 + "<td align=\"center\">到期日期</td>";
                            row0 = row0 + "<td align=\"center\">人事審核</td>";
                            row0 = row0 + "<td align=\"center\">刪除</td>";

                            for(var i=0;i<JData.OVER_DATE.length;i++){

                                row0 = row0 + "<tr><td align='center'>" ;
                                row0 = row0 + JData.OVER_DATE[i] ;
                                row0 = row0 + "</td><td>" ;
                                row0 = row0 + JData.DO_TIME_1[i] ;
                                row0 = row0 + "</td><td>" ;
                                row0 = row0 + JData.DO_TIME_2[i] ;
                                row0 = row0 + "</td><td>" ;
                                row0 = row0 + JData.NOUSE_TIME[i] ;
                                row0 = row0 + "</td><td>" ;
                                row0 = row0 + JData.DUE_DATE[i] ;
                                row0 = row0 + "</td><td>" ;
                                var p_checkn="";
                                if (JData.PERSON_CHECK[i]=='1')
                                    p_checkn='已審核';
                                else
                                    p_checkn='待審核';

                                row0 = row0 + p_checkn ;
                                row0 = row0 + "</td><td>" ;

                                if (JData.PERSON_CHECK[i]=='1')
                                    row0 = row0  + "已審核無法刪除";
                                else
                                    row0 = row0  + "<button type='button' class='btn-danger' name='delete' id='delete' onclick='CRUD(3," + JData.OVER_DATE[i] + ")' title='刪除'>刪除</button>" ;
                                row0 = row0  + "</td></tr>";
                            }

                            row0 = row0 + "</tbody></table>";
                            $('#_content').append(row0);
                    }//else

                }
                else if (oper == 3) { //刪除
                    toastr["success"]("資料刪除成功!");
                    CRUD(0); //reload
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}