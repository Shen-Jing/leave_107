$(
    function (){
        $("body").tooltip({
            selector: "[title]"
        });

        $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'qry_year'},
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected disabled class='text-hide'>請選擇學年度</option>";
                $ ('#class_year').append(row0);
                for (var i = JData - 1 ; i <= JData + 1 ; i++)
                {
                    if (i == JData)
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#class_year').append(row);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);
            alert("error!!");}
        });
        $('#class_year,#class_acadm').change( // 抓取區域選完的資料
            function(e) {
                if ($(':selected', this).val() !== '') {
                    CRUD(0); //query
                }
            }
        );
  }
);


var serailnoval;
var classnoval;
function CRUD(oper) {
    $.ajax({
        url: 'ajax/class_year_2_ajax.php',
        data: { oper: 'query'},
        type: 'POST',
        dataType: "json",
        success: function(JData) {
          $('#class_content').empty();

          if (JData.error_code)
              toastr["error"](JData.error_message);
          else{
          if (JData["CLASS_NAME"].length == "0"){
              var row_part_new = "<center style='color:red'>本月無任何記錄。</center><br>";
              $('#class_content').append(row_part_new);

          }else {
            var row0 ="";
            row0 = row0 + "<table class='table table-bordered col-md-8'><tbody><tr>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>上課班別</td>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>科目名稱</td>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>原上課時間</td>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>補課時間</td>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>補課教室</td>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>補課節次</td>";
            //row0 = row0 + "<td class='td1' style='text-align:center;'>刪除或修改申請單</td></tr>";


            for(var i = 0 ; i < JData["CLASS_NAME"].length ; i++){

              row0 = row0 + "<tr><td  style='text-align:center;'>" ;
              row0 = row0 + JData.CLASS_NAME[i];
              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              row0 = row0 + JData.CLASS_SUBJECT[i];
              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              row0 = row0 + JData.CLASS_DATE[i];
              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              row0 = row0 + JData.CLASS_DATE2[i];
              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              row0 = row0 + JData.CLASS_ROOM[i];

              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              row0 = row0 + JData.CLASS_SECTION2[i];
              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              //row0 = row0 + "</td><td  style='text-align:center;'>" ;
              //row0 = row0 + "<button id='editrow' class='btn-primary' type='button' onclick='EditData("+serialno+"); title='編輯'>修改</button><button id='delrow' class='btn-danger' type='button' title='刪除'>刪除</button>" ;
              //row0=row0+"<button type='button' class='btn-primary' name='edit' id='edit' onclick='EditDataFunc("+serialno+","+classno+")' title='編輯'>編輯</button><button type='button' class='btn-danger' name='delete' id='delete' onclick='' title='刪除'>刪除</button>" ;
              row0 = row0 + "</td></tr>";

            }
            row0 = row0 + "</tbody></table>";
            $('#class_content').append(row0);
          }
        }

        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });

}

function EditDataFunc(serialno,classno)
{

  var cy=$ ('#qry_class_year').val();
  var ca=$ ('#qry_acadm').val();
  $('#holiday_time').empty();
  $('#holidy_mark').empty();
  $('#subject-name').empty();
  $('#class-name').empty();
  $('#ocyear').empty();
  $('#ocmonth').empty();
  $('#ocday').empty();
  $('#ccyear').empty();
  $('#ccmonth').empty();
  $('#ccday').empty();
  $.ajax({
      url: 'ajax/class_add_ajax.php',
      data: { oper: 'edit_class' ,serialnoVar: serialno,class_year: cy, class_acadm: ca,class_no: classno},
      type: 'POST',
      dataType: "json",
      success: function(JData) {

        if (JData.error_code)
            toastr["error"](JData.error_message);
        else{
          var row0 ="";
          row0=row0+JData["byear"]+"/"+JData["bmonth"]+"/"+JData["bday"]+"~"+JData["eyear"]+"/"+JData["emonth"]+"/"+JData["eday"];
          $('#holiday_time').append(row0);


          row0="";
          row0=row0+JData["holidaymark"];
          $('#holidy_mark').append(row0);
          classnoval=JData["class_no"];
          row0 = "<$option selected disabled class='text-hide'>選擇科目</option>";
          row0 =row0+ "<option value=1>電子學</option>";
          row0 =row0+ "<option value=2>線性代數</option>";
          row0 =row0+ "<option value=3>遊戲設計</option>";
          row0 =row0+ "<option value=4>演算法</option>";
          row0 =row0+ "<option value=5>作業系統</option>";
          $('#subject-name').append(row0);
          var classname="資工三"
          row0=classname;
          $('#class-name').append(row0);


          row0 = "<$option selected disabled class='text-hide'>請選擇年份</option>";
          $ ('#ocyear').append(row0);
          for (var i = JData["year"] - 1 ; i <= JData["year"]+1 ; i++)
          {
              if (i == JData["year"])
                  row = "<option value=" +i+ " selected>" + i + "</option>";
              else
                  row = "<option value=" +i+ ">" + i + " </option>";
              $ ('#ocyear').append(row);
          }

          row0 = "<$option selected disabled class='text-hide'>請選擇月份</option>";
          $ ('#ocmonth').append(row0);
          for (var i = 1; i <= 12 ; i++)
          {
                  row = "<option value=" +i+ ">" + i + " </option>";
              $ ('#ocmonth').append(row);
          }

          row0 = "<$option selected disabled class='text-hide'>請選擇日期</option>";
          $ ('#ocday').append(row0);
          for (var i = 1; i <= 31 ; i++)
          {
                  row = "<option value=" +i+ ">" + i + " </option>";
              $ ('#ocday').append(row);
          }

          row0 = "<$option selected disabled class='text-hide'>請選擇年份</option>";
          $ ('#ccyear').append(row0);
          for (var i = JData["year"] - 1 ; i <= JData["year"]+1 ; i++)
          {
              if (i == JData["year"])
                  row = "<option value=" +i+ " selected>" + i + "</option>";
              else
                  row = "<option value=" +i+ ">" + i + " </option>";
              $ ('#ccyear').append(row);
          }

          row0 = "<$option selected disabled class='text-hide'>請選擇月份</option>";
          $ ('#ccmonth').append(row0);
          for (var i = 1; i <= 12 ; i++)
          {
                  row = "<option value=" +i+ ">" + i + " </option>";
              $ ('#ccmonth').append(row);
          }

          row0 = "<$option selected disabled class='text-hide'>請選擇日期</option>";
          $ ('#ocday').append(row0);
          for (var i = 1; i <= 31 ; i++)
          {
                  row = "<option value=" +i+ ">" + i + " </option>";
              $ ('#ccday').append(row);
          }


        }

      },
      error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}

function Send()
{
  var oyear=$ ('#ocyear').val();
  var omonth=$ ('#ocmonth').val();
  var oday=$ ('#ocday').val();

  var cyear=$ ('#ccyear').val();
  var cmonth=$ ('#ccmonth').val();
  var cday=$ ('#ccday').val();

  var class_subject=$('#subject-name').val();
  var class_section2=$ ('#class_section2').val();
  var class_room=$('#class_room').val();
  var class_memo=$('#class_memo').val();

  var class_name=$('#class-name').val();

  var class_year=$ ('#qry_class_year').val();
  var class_acadm=$ ('#qry_acadm').val();

  $.ajax({
      url: 'ajax/class_add_ajax.php',
      data: { oper: 'send' ,classsubject:class_subject,byear:oyear,bmonth:omonth,bday:oday
             ,eyear:cyear,emonth:cmonth,eday:cday,classsection2:class_section2,serialno:serailnoval,class_no:classnoval,class_room:class_room,class_memo:class_memo,class_year:class_year,class_acadm:class_acadm},
      type: 'POST',
      dataType: "json",
      success: function() {
        toastr["success"]("處理完成!!");
        $("#ChangeModal2").modal("hide");
      },
      error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}
function CheckData()
{
  var oyear=$ ('#ocyear').val();
  var omonth=$ ('#ocmonth').val();
  var oday=$ ('#ocday').val();

  var cyear=$ ('#ccyear').val();
  var cmonth=$ ('#ccmonth').val();
  var cday=$ ('#ccday').val();

  var class_subject=$('#subject-name').val();
  var class_section2=$ ('#class_section2').val();
  var class_room=$('#class_room').val();
  var class_memo=$('#class_memo').val();

  var class_name=$('#class-name').val();

  var class_year=$ ('#qry_class_year').val();
  var class_acadm=$ ('#qry_acadm').val();

  $.ajax({
      url: 'ajax/class_add_ajax.php',
      data: { oper: 'ch' },
      type: 'POST',
      dataType: "json",
      success: function() {
        if(class_section2=="")
        {
          toastr["error"]("補課節次未填寫!");
        }else if(class_room=="")
          toastr["error"]("補課教室未填寫!");
        else
        {

          Send();
        }

      },
      error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}
function closeM()
{
  $("#ChangeModal2").modal("hide");
}
