$( // 表示網頁完成後才會載入
    function (){

        $ ("body").tooltip({
            selector: "[title]"
        });

        $ .ajax({
            url: 'ajax/class_all_ajax.php',
            data: { oper: 'qry_year' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $ ('#qry_year').append(row0);
                for (var i = JData["year"] - 1 ; i <= JData["year"]+1 ; i++)
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

                //CRUD(0);//首次進入頁面query
            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $ .ajax({
            url: 'ajax/class_all_ajax.php',
            data: { oper: 'qry_dpt' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected value=''>請選擇學院</option>";
                $ ('#qry_c').append(row0);

                for (var i = 0; i < JData.DEPT_NO.length ; i++)
                {
                    var depart = JData.DEPT_NO[i];
                    var dept_name = JData.DEPT_FULL_NAME[i];
                    row = "<option value=" + depart + ">" +depart+ dept_name + "</option>";

                    $ ('#qry_c').append(row);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });


        $ ('#qry_year,#qry_month,#qry_c').change( // 抓取區域選完的資料
            function(e) {
                if ($ (':selected', this).val() !== '')
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
    dptval= $ ('#qry_c').val();

    $('#Btable').DataTable({
        "scrollY": "500px",
        "displayLength": 10,
        "destroy": true,
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"}
        ],
        "ajax": {
            url: 'ajax/class_all_ajax.php',
            data: { oper: 0, p_year: yyval, p_month: mmval, c_menu: dptval},
            type: 'POST',
            dataType: 'json'
        },
        "columns": [
            { "name": "DEPT_SHORT_NAME" },
            { "name": "EMPL_CHN_NAME" },
            { "name": "CODE_CHN_ITEM" },
            { "name": "ABROAD" },
            { "name": "POVDATEB" },
            { "name": "POVDATEE" },
            { "name": "POVTIMEB" },
            { "name": "POVTIMEE" },
            { "name": "POVDAYS" },
            { "name": "ACAMDATE" },
            { "name": "ACAMDATE_2" },
            { "name": "ACAMDATE_3" },
            { "name": "DETAIL" }
        ]
    });
}

function View(serialno,depart)
{
  $('#fullscrModal').modal('show');
  $('#class_content').empty();
  $('#class_content2').empty();
  $('#title_name').empty();
    $.ajax({
            url: 'ajax/class_all_ajax.php',
            data: { oper: 'view', serialno: serialno ,depart: depart},
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var title=JData["NAME"];
                var title_text=title+"教師本次差假已填寫之紀錄";
                $('#title_name').append(title_text);
                var row0 ="";
                var povtype= JData["POVTYPE"];
                var poremark=JData["POREMARK"];
                //alert(JData["POVTYPE"]);
                row0 = row0 + "<table class='table table-bordered col-md-8'><tbody>";
                row0 = row0 + "<tr><td colspan='8'>假別:"+povtype+"<br>事由:"+poremark+"</td></tr><tr>";
                row0 = row0 + "<td>上課班別</td>";
                row0 = row0 + "<td>開課代碼</td>";
                row0 = row0 + "<td>科目名稱</td>";
                row0 = row0 + "<td>原上課時間</td>";
                row0 = row0 + "<td>補課時間</td>";
                row0 = row0 + "<td>補課教室</td>";
                row0 = row0 + "<td>補課節次</td>";
                row0 = row0 + "<td>備註</td></tr>";
                for(var i = 0 ; i < JData["dtl"]['CLASS_NAME'].length;i++)
                {
                  row0 = row0 + "<tr><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["dtl"]["CLASS_NAME"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["dtl"]["CLASS_SELCODE"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["dtl"]["CLASS_SUBJECT"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["dtl"]["CLASS_DATE"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["dtl"]["CLASS_DATE2"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["dtl"]["CLASS_ROOM"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["dtl"]["CLASS_SECTION2"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["dtl"]["CLASS_MEMO"][i];
                  row0 = row0 + "</td></tr>";
                }

                row0 = row0 + "</tbody></table>";
                $('#class_content').append(row0);

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        $.ajax({
                url: 'ajax/class_all_ajax.php',
                data: { oper: 'sign', serialno: serialno ,depart: depart,level:1},
                type: 'POST',
                dataType: "json",
                success: function(JData) {
                  var row0 ="";
                  row0 = row0 + "<table class='table table-bordered col-md-8'><tbody><tr>";
                  row0 = row0 + "<td class='td1' style='text-align:center;'>簽核者</td>";
                  row0 = row0 + "<td class='td1' style='text-align:center;'>簽核日期</td>";
                  row0 = row0 + "<td class='td1' style='text-align:center;'>被退原因</td></tr>";
                  row0 = row0 + "<tr><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["EMPL_CHN_NAME"];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["ACADM_DATE"];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["ACADM_REASON"];
                  row0 = row0 + "<tr><td  style='text-align:center;'>" ;
                  row0 = row0 + "組長";
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["ACADM2_DATE"];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["ACADM2_REASON"];
                  row0 = row0 + "<tr><td  style='text-align:center;'>" ;
                  row0 = row0 + "教務長(或進修學院院長)";
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["ACADM3_DATE"];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["ACADM3_REASON"];
                  row0 = row0 + "</td></tr>";

                  row0 = row0 + "</tbody></table>";
                  $('#class_content2').append(row0);
                },
                error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
            });
}
