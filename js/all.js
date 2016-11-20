$( // 表示網頁完成後才會載入
    function (){

        $ ("body").tooltip({
            selector: "[title]"
        });

        $ .ajax({
            url: 'ajax/all_ajax.php',
            data: { oper: 'qry_year' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $ ('#qry_year').append(row0);
                for (var i = JData["year"] - 3 ; i <= JData["year"] ; i++)
                {
                    if (i == JData["year"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#qry_year').append(row);
                }

                row0 = "<$option selected disabled class='text-hide'>請選擇月份</option>";
                $ ('#qry_month').append(row0);
                for (var i = 1; i <= 12 ; i++)
                {
                    if (i == JData["month"] )
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#qry_month').append(row);
                }

                CRUD(0);//首次進入頁面query
            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $ .ajax({
            url: 'ajax/all_ajax.php',
            data: { oper: 'qry_dpt' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected disabled class='text-hide'>請選擇單位</option>";
                $ ('#qry_dpt').append(row0);

                for (var i = 0; i < JData.DEPT_NO.length ; i++)
                {
                    var depart = JData.DEPT_NO[i];
                    var dept_name = JData.DEPT_FULL_NAME[i];
                    row = "<option value=" + depart + ">" + dept_name + "</option>";

                    $ ('#qry_dpt').append(row);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $ .ajax({
            url: 'ajax/all_ajax.php',
            data: { oper: 'qry_type' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected disabled class='text-hide'>請選擇假別</option>";
                $ ('#qry_type').append(row0);
                for (var i = 0; i < JData.CODE_FIELD.length ; i++)
                {
                    row = "<option value=" + JData.CODE_FIELD[i] + ">" + JData.CODE_CHN_ITEM[i] + "</option>";
                    $ ('#qry_type').append(row);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $ ('#qry_year,#qry_month,#qry_dpt,#qry_type').change( // 抓取區域選完的資料
            function(e) {
                if ($ (':selected', this).val() !== '' && $('#qry_dpt').val() != null && $('#qry_type').val() != null)
                {
                    CRUD(0); //query
                }
            }
        );
    }
);

function CRUD(oper, id) {
    id = id || ''; //預設值
    var yyval, mmval, dptval, typeval;

    yyval = $ ('#qry_year').val();
    mmval = $ ('#qry_month').val();
    dptval= $ ('#qry_dpt').val();
    typeval = $ ('#qry_type').val();

    $ .ajax({
        url: 'ajax/all_ajax.php',
        data: { oper: 0, p_year: yyval, p_month: mmval, dpt: dptval, type: typeval },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else{
                    if (oper == "0")
                    { //查詢

                        $('#_content').empty();
                        if( JData.EMPL_CHN_NAME.length == 0 )
                        {
                            var row = "<div class='table-responsive'><table class='table table-bordered table-striped'><thread><tr>";
                            row = row + "<th style='text-align:center;'>單位</th>";
                            row = row + "<th style='text-align:center;'>姓名</th>";
                            row = row + "<th style='text-align:center;'>假別</th>";
                            row = row + "<th style='text-align:center;'>起始日</th>";
                            row = row + "<th style='text-align:center;'>終止日</th>";
                            row = row + "<th style='text-align:center;'>起始</th>";
                            row = row + "<th style='text-align:center;'>天/時</th>";
                            row = row + "<th style='text-align:center;'>單位簽</th>";
                            row = row + "<th style='text-align:center;'>人事承辦</th>";
                            row = row + "<th style='text-align:center;'>人事主任</th>";
                            row = row + "<th style='text-align:center;'>秘書簽 </th>";
                            row = row + "<th style='text-align:center;'>填寫日期</th></tr></thread></table></div>";

                            row = row + "<center style='color:red'>無記錄</center>";
                            $('#_content').append(row);
                        }
                        else
                        {
                            for(var i = 0 ; i < JData.EMPL_CHN_NAME.length ; i++)
                            {
                                var poname = JData.EMPL_CHN_NAME[i];
                                var pocard = JData.POCARD[i];
                                var povtype = JData.CODE_CHN_ITEM[i];
                                var povdateB = JData.POVDATEB[i];
                                var povdatee = JData.POVDATEE[i];
                                var povhours = JData.POVHOURS[i];
                                var povdays  = JData.POVDAYS[i];
                                var povtimeb = JData.POVTIMEB[i];
                                var povtimee = JData.POVTIMEE[i];
                                var abroad = JData.ABROAD[i];
                                var agentno = JData.AGENTNO[i];
                                var serialno = JData.SERIALNO[i];
                                var twosignd = JData.TWOSIGND[i];
                                var depart = JData.DEPART[i];
                                var perone_signd = JData.PERONE_SIGND[i];
                                var pertwo_signd = JData.PERTWO_SIGND[i];
                                var secone_signd = JData.SECONE_SIGND[i];
                                var deptname = JData.DEPT_SHORT_NAME[i];
                                var appdate = JData.APPDATE[i];

                                if (twosignd == '')
                                   twosignd = '-';
                                if (perone_signd == '')
                                   perone_signd = '-';
                                if (pertwo_signd == '')
                                   pertwo_signd = '-';
                                if (secone_signd == '')
                                    secone_signd = '-';

                                row = "<div class='table-responsive'><table class='table table-bordered table-striped'><thread><tr>";
                                row = row + "<th style='text-align:center;'>單位</th>";
                                row = row + "<th style='text-align:center;'>姓名</th>";
                                row = row + "<th style='text-align:center;'>假別</th>";
                                row = row + "<th style='text-align:center;'>起始日</th>";
                                row = row + "<th style='text-align:center;'>終止日</th>";
                                row = row + "<th style='text-align:center;'>起始</th>";
                                row = row + "<th style='text-align:center;'>天/時</th>";
                                row = row + "<th style='text-align:center;'>單位簽</th>";
                                row = row + "<th style='text-align:center;'>人事承辦</th>";
                                row = row + "<th style='text-align:center;'>人事主任</th>";
                                row = row + "<th style='text-align:center;'>秘書簽 </th>";
                                row = row + "<th style='text-align:center;'>填寫日期</th></tr><tr>";

                                row = row + "<td style='text-align:center;'>" + deptname + "</td>" ;//單位
                                row = row + "<td style='text-align:center;'>" + poname + "</td>" ;//中文名字
                                row = row + "<td style='text-align:center;' width=\"12%\">" + povtype + "</td>";//假別"
                                row = row + "<td style='text-align:center;'>" + povdateB + "</td>" ; //起始日期
                                row = row + "<td style='text-align:center;'>" + povdatee + "</td>" ;//結束日期
                                row = row + "<td style='text-align:center;'>" + povtimeb + "</td>" ;//起始時間
                                row = row + "<td style='text-align:center;'>" + povdays + "/" + povhours+ "</td>" ;  //總天數和時數
                                row = row + "<td style='text-align:center;'>" + twosignd + "</td>" ;
                                row = row + "<td style='text-align:center;'>" + perone_signd + "</td>" ;
                                row = row + "<td style='text-align:center;'>" + pertwo_signd + "</td>" ;
                                row = row + "<td style='text-align:center;'>" + secone_signd + "</td>" ;
                                row = row + "<td style='text-align:center;'>" + appdate + "</td></tr>" ;
                            }
                            row = row + "</thread></table></div>";
                            $ ('#_content').append(row);
                        }

                    }

            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}