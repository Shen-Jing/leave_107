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
                for (var i = 99; i <=JData.YEAR; i++) {
                    if (i==JData.YEAR)
                        var row = "<option value=" +i+ " selected> </option>";  
                    else
                        var row = "<option value=" +i+ "> </option>"; 
                    $('#qry_year').append(row);
                }
                //CRUD(0);//query
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
    
    $.ajax({
        url: 'ajax/overtime_query_ajax.php',
        data: { oper: '0' , p_menu:yyval },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else{
                if (oper == "0") { //查詢

                    $('#_content').empty();

                    if (JData.COUNT==0){
                        var row_part_new = "<center style='color:red'>您目前無任何加班記錄。</center><br>"+JData.COUNT[0];
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
                            
                            //$row = db_fetch_array($res);

                            /*while (OCIFetchInto($res, $row, OCI_ASSOC)){//liru update
                                $over_date = $row['OVER_DATE'];
                                $time_1 = $row['DO_TIME_1'];
                                $time_2 = $row['DO_TIME_2'];
                                $nouse  = $row['NOUSE_TIME'];
                                $due_date = $row['DUE_DATE'];
                                $p_check = $row['PERSON_CHECK'];
                                if ($p_check=='1')
                                    $p_checkn='已審核';
                                else
                                    $p_checkn='待審核';
                                */
                                
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
                                    row0 = row0  + "<a href=\"overtime_delete.php?over_date=$over_date\"  onClick=\"return(       confirm('你確定要刪除嗎？'))\">刪除</a>" ;
                                row0 = row0  + "</td></tr>";
                            }
                        
                            row0 = row0 + "</tbody></table>";
                            $('#_content').append(row0);
                    }//else

                } 
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}