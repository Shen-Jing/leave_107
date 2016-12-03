$( // 表示網頁完成後才會載入
    function(){

        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/call_off_ajax.php',
            data: { oper: 'qry' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $('#qry_year').append(row0);
                for (var i = 99; i <= JData["year"] ; i++) {
                    if (i == JData["year"])
                        var row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        var row = "<option value=" +i+ ">" + i + " </option>";
                    $('#qry_year').append(row);
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


        $('#qry_year,#qry_month').change( // 抓取區域選完的資料
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0); //query
                }
            }
        );
    }
);

function CRUD(oper, id)
{
    id = id || ''; //預設值
    var yyval, mmval, dptval, typeval;
    yyval = $ ('#qry_year').val();
    mmval = $ ('#qry_month').val();

    if (oper == 2)
        if (!confirm("是否確定要取消?")) return false;

    $.ajax({
        url: 'ajax/call_off_ajax.php',
        data: { oper: oper, old_id: id, year:yyval, month: mmval },
        type: 'POST',
        dataType: "json",
        success: function(JData)
        {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else
            {
                if (oper == "0")
                { //查詢

                    $('#_content1,#_content2').empty();

                    var row0 ="";
                    var row1 ="";

                    row0 = row0 + "<table class='table table-bordered col-md-8'><tbody><tr>";
                    row0 = row0 + "<td class='td1' style='text-align:center;'>姓名</td>";
                    row0 = row0 + "<td class='td1' style='text-align:center;'>假別</td>";
                    row0 = row0 + "<td class='td1' style='text-align:center;'>起始日期</td>";
                    row0 = row0 + "<td class='td1' style='text-align:center;'>終止日期</td>";
                    row0 = row0 + "<td class='td1' style='text-align:center;'>起始時間</td>";
                    row0 = row0 + "<td class='td1' style='text-align:center;'>終止時間</td>";
                    row0 = row0 + "<td class='td1' style='text-align:center;'>總時數</td>";
                    row0 = row0 + "<td class='td1' style='text-align:center;'>職務代理人</td>";
                    row0 = row0 + "<td class='td1' style='text-align:center;'>取消</td>";
                    row1 = row0;

                    for(var i = 0 ; i<JData[0][0].length ; i++)
                    {
                        //var str = "o_0";
                        row0 = row0 + "<tr><td  style='text-align:center;'>" ;
                        row0 = row0 + JData[0][0][i] ;
                        row0 = row0 + "</td><td  style='text-align:center;'>" ;
                        row0 = row0 + JData[0][1][i] ;
                        row0 = row0 + "</td><td  style='text-align:center;'>" ;
                        row0 = row0 + JData[0][2][i] ;
                        row0 = row0 + "</td><td  style='text-align:center;'>" ;
                        row0 = row0 + JData[0][3][i] ;
                        row0 = row0 + "</td><td  style='text-align:center;'>" ;
                        row0 = row0 + JData[0][4][i] ;
                        row0 = row0 + "</td><td  style='text-align:center;'>" ;
                        row0 = row0 + JData[0][5][i] ;
                        row0 = row0 + "</td><td  style='text-align:center;'>" ;
                        row0 = row0 + JData[0][6][i] + "天" + JData[0][7][i] + "時";
                        row0 = row0 + "</td><td  style='text-align:center;'>" ;
                        row0 = row0 + JData[0][8][i];
                        row0 = row0 + "</td><td  style='text-align:center;'>" ;
                        row0 = row0 + "<button type='button' class='btn-default' name='cancel1' id='cancel1' onclick='CRUD(2,1"+i+");' title='取消'>取消</button>" ;
                        row0 = row0 + "</td></tr>";
                    }

                    row0 = row0 + "</tbody></table>";
                    $('#_content1').append(row0);

                    for(var i = 0 ; i<JData[1][0].length ; i++)
                    {
                        row1 = row1 + "<tr><td  style='text-align:center;'>" ;
                        row1 = row1 + JData[1][0][i] ;
                        row1 = row1 + "</td><td  style='text-align:center;'>" ;
                        row1 = row1 + JData[1][1][i] ;
                        row1 = row1 + "</td><td  style='text-align:center;'>" ;
                        row1 = row1 + JData[1][2][i] ;
                        row1 = row1 + "</td><td  style='text-align:center;'>" ;
                        row1 = row1 + JData[1][3][i] ;
                        row1 = row1 + "</td><td  style='text-align:center;'>" ;
                        row1 = row1 + JData[1][4][i] ;
                        row1 = row1 + "</td><td  style='text-align:center;'>" ;
                        row1 = row1 + JData[1][5][i] ;
                        row1 = row1 + "</td><td  style='text-align:center;'>" ;
                        row1 = row1 + JData[1][6][i] + "天" + JData[1][7][i] + "時";
                        row1 = row1 + "</td><td  style='text-align:center;'>" ;
                        row1 = row1 + JData[1][8][i];
                        row1 = row1 + "</td><td  style='text-align:center;'>" ;
                        row1 = row1 + "<button type='button' class='btn-default' name='cancel2' id='cancel2' onclick='CRUD(2,2"+i+");' title='取消'>取消</button>" ;
                        row1 = row1 + "</td></tr>";
                    }

                    row1 = row1 + "</tbody></table>";
                    $('#_content2').append(row1);
                }
                else if (oper == 2)
                { //刪除
                    alert(JData);
                    CRUD(0); //reload
                }
            }
        },
        beforeSend: function() {
            $('#loading1,#loading2').show();
        },
        complete: function() {
            $('#loading1,#loading2').hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}