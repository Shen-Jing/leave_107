$( // 表示網頁完成後才會載入
    function(){

        $("body").tooltip({
            selector: "[title]"
        });
        $.ajax({
            url: 'ajax/search_person_ajax.php',
            data: { oper: 'qry' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
                $('#qry_year').append(row0);
                for (var i = 95; i <= JData["year"]+1 ; i++) {
                    if (i == JData["year"])
                        var row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        var row = "<option value=" +i+ ">" + i + " </option>";
                    $('#qry_year').append(row);
                }
                $('#signed').append(JData["begin_date"] + "~" + JData["end_date"] + "--已簽核完成");
                $('#unsigned').append(JData["begin_date"] + "~" + JData["end_date"] + "--簽核中");
                row = "<table class='table table-bordered'><tr><td class='td1 col-md-3' colspan='2' >姓名</td><td class='col-md-3' colspan='2'>" + JData["name"] + "</td><td class='td1 col-md-3' colspan='3'>員工編號</td><td class='col-md-3' colspan='2'>" + JData["userid"] + "</td></tr>";
                row = row + "<tr><td class='td1 col-md-2'>假別</td><td class='td1 col-md-1'>總天數</td><td class='td1 col-md-1'>總時數</td>";
                row = row + "<td class='td1 col-md-2'>假別</td><td class='td1 col-md-1'>總天數</td><td class='td1 col-md-1'>總時數</td><td class='td1 col-md-2'>假別</td><td class='td1 col-md-1'>總天數</td><td class='td1 col-md-1'>總時數</td></tr>";
                $('#_content1,#_content2').append(row);

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

function CRUD(oper, id)
{
    id = id || ''; //預設值
    var yyval, dptval, typeval;
    yyval = $ ('#qry_year').val();

    $.ajax({
        url: 'ajax/search_person_ajax.php',
        data: { oper: oper, year:yyval },
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

                    $('#_content1,#_content2,#signed,#unsigned').empty();
                    $('#signed').append(JData["begin_date"] + "~" + JData["end_date"] + "--已簽核完成");
                    $('#unsigned').append(JData["begin_date"] + "~" + JData["end_date"] + "--簽核中");
                    var row ="";
                	var row1 ="";
                    row = "<table class='table table-bordered'><tr><td class='td1 col-md-3' colspan='2' >姓名</td><td class='col-md-3' colspan='2'>" + JData["name"] + "</td><td class='td1 col-md-3' colspan='3'>員工編號</td><td class='col-md-3' colspan='2'>" + JData["userid"] + "</td></tr>";
                    row = row + "<tr><td class='td1 col-md-2'>假別</td><td class='td1 col-md-1'>總天數</td><td class='td1 col-md-1'>總時數</td>";
                    row = row + "<td class='td1 col-md-2'>假別</td><td class='td1 col-md-1'>總天數</td><td class='td1 col-md-1'>總時數</td><td class='td1 col-md-2'>假別</td><td class='td1 col-md-1'>總天數</td><td class='td1 col-md-1'>總時數</td></tr>";
                    row1 = row;
                    //$('#_content1,#_content2').append(row);

                	var col = 1;

                    //row0 = row0 + "<tr><td>姓名</td><td>" + name; ?></td><td>員工編號</td><td><? echo $userid; ?></td></tr>

                	for(var i = 0 ; i < 15 ; i++)
                	{

                		if(col == 1){
                			row = row + "<tr>";  //出差、公假、事假 三個是一列
                			row1 = row1 + "<tr>";
                		}


                		row = row + "<td>" + JData[0]["v"][i] + "</td><td>" + JData[0]["pohdaye"][i] + "</td><td>" + JData[0]["pohoure"][i] + "</td>";
                		row1 = row1 +"<td>" + JData[1]["v"][i] + "</td><td>" + JData[1]["pohdaye"][i] + "</td><td>" + JData[1]["pohoure"][i] + "</td>";

                		col++;
                		if(col == 4)
                		{
                			col=1;
                			row = row + "</tr>";
                			row1 = row1 + "</tr>";
                		}
                	}//for
                	$('#_content1').append(row);
                	$('#_content2').append(row1);
                }

            }
        },
        beforeSend: function() {
            $('#loading1,#loading2').show();
        },
        complete: function() {
            $('#loading1,#loading2').hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);/*alert(xhr.responseText);*/}
    });
}