$( // 表示網頁完成後才會載入
    function p_menu_onChange(){

        $("body").tooltip({
            selector: "[title]"
        });

        $.ajax({
            url: 'ajax/search_card_ajax.php',
            data: { oper: 'qry_year' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $('#qry_year').append(row0);
                for (var i = 97; i <= parseInt( JData.END_YEAR ) ; i++) {
                    if (i == parseInt( JData.END_YEAR ))
                        var row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        var row = "<option value=" +i+ ">" + i + " </option>";
                    $('#qry_year').append(row);
                }

                var row0 = "<option selected disabled class='text-hide'>請選擇月份</option>";
                $('#qry_month').append(row0);
                for (var i = 1; i <= 12 ; i++) {
                    if (i == parseInt( JData.END_MONTH ))
                        var row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        var row = "<option value=" +i+ ">" + i + " </option>";
                    $('#qry_month').append(row);
                }

                CRUD(0);//首次進入頁面query
            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });


        $('#qry_year,#qry_month').change( // 抓取區域選完的資料
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
    var yycho,yyval, mmcho, mmval;

    yycho =document.form1.qry_year.selectedIndex;
    yyval=document.form1.qry_year.options[yycho].value;
    mmcho =document.form1.qry_month.selectedIndex;
    mmval=document.form1.qry_month.options[mmcho].value;

    $.ajax({
        url: 'ajax/search_card_ajax.php',
        data: { oper: oper, p_year: yyval, p_month: mmval },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else{
                if (oper == "0")
                { //查詢

                    //$('#_content').empty();

                    var i = 0;
                    var z = 0;
                    var j = 0;
                    var memo = [];
                    var day  = [];
                    var meno = [];
                    var do_time = [];
                    var day_day  = [];
                    var time  = [];
                    var day_meno = [];
                    var day_memo = [];

                    for(var k = 0 ; k < JData.DAY.length ; k++)
                    {
                        memo[i] = '';
                        day[i]  = JData.DAY[k];
                        memo[i] = JData.MEMO[k];
                        do_time[i] = JData.DO_TIME[k];

                        if (i == 0)
                        {
                             day_day[z]  = day[i];
                             time[z] = [];
                             time[z][j]  = do_time[i];
                             day_meno[z] = meno[i];
                        }
                        else if (day[i] != day[i - 1])
                        {
                            z++;
                            j=0;//不同天時

                            day_day[z] = day[i];
                            time[z] = [];
                            time[z][j] = do_time[i];
                            day_memo[z] = memo[i];
                        }
                        else
                        {
                            j++;
                            time[z][j] = do_time[i];
                        }
                        i++; //下一筆
                    }

                    var row0 ="";
                    row0 = row0 + "<table class='table table-striped table-bordered dt-responsive nowrap col-md-8' width='100%'><tbody><tr>";
                    row0 = row0 + "<td align=\"center\">日期</td>";
                    row0 = row0 + "<td align=\"center\" colspan=\"6\">刷卡時間(含加班時間)</td>";
                    row0 = row0 + "<td align=\"center\">附註</td>";

                    for (var l = 0 ; l <= z; l++)
                    {
                        row0 = row0 + "<tr><td>" + day_day[l] + "</td>";

                        for (var m=0 ; m < 6 ; m++)
                        {

                            if ( ( typeof time[l][m] === "undefined" ) || time[l][m] == '' )
                                row0 = row0 + "<td>-----</td>";
                            else
                                row0 = row0 + "<td>" + time[l][m] + "</td>";
                        }

                        if (( typeof day_memo[l] === "undefined" ) || day_memo[l] == '')
                            row0 = row0 + "<td>-----</td></tr>";
                        else
                            row0 = row0 + "<td>" + day_memo[l] + "</td></tr>";
                    }

                    row0 = row0 + "<tr><td colspan='4'><div class='alert alert-warning'><i class='fa fa-warning'>注意!";
                    row0 = row0 + "<ol><li>加班應事先以書面專案簽准。</li></ol></i></div></td></tr></tbody></table>";

                    $('#_content').append(row0);

                }

            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}