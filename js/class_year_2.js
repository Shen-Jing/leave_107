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
                row0 = "<option selected disabled value = '' class='text-hide'>請選擇學年度</option>";
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
                if ($('#class_year').val() !== null && $('#class_acadm').val() !== null) {
                    CRUD(0); //query
                }
            }
        );

  }
);


function CRUD(oper) {

    if(oper == 0)
    {
        //本次填寫紀錄
        $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'qry_record', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val() },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
              $('#_content').empty();

              if (JData.error_code)
                  toastr["error"](JData.error_message);
              else{
              if (JData.length == "0"){
                  var row_part_new = "<center style='color:red'>無任何記錄。</center>";
                  $('#_content').append(row_part_new);

              }else {
                var row0 ="";
                row0 = row0 + "<table class='table table-bordered col-md-8'><tbody><tr>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>上課班別</td>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>科目名稱</td>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>原上課時間</td>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>補課時間</td>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>補課教室</td>";
                row0 = row0 + "<td class='td1' style='text-align:center;'>補課節次</td>";
                if(JData["SERIALNO"].length != 0)
                    row0 = row0 + "<td class='td1' style='text-align:center;'>刪除或修改申請單</td>";
                row0 = row0 + "</tr>";

                for(var i = 0 ; i < JData["CLASS_NAME"].length ; i++){

                  row0 = row0 + "<tr><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_NAME"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_SUBJECT"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_DATE"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_DATE2"][i];
                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_ROOM"][i];

                  row0 = row0 + "</td><td  style='text-align:center;'>" ;
                  row0 = row0 + JData["CLASS_SECTION2"][i];
                  if( JData["SERIALNO"][i] != null )
                  {
                    row0 = row0 + "</td><td  style='text-align:center;'>" ;
                    row0 = row0 + "<button id='delete' name='delete' class='btn-danger' type='button' onclick='DeleteRow(" + JData["CLASS_NO"][i] + "," + JData["SERIALNO"][i] + ")'; title='刪除'>刪除</button>" ;
                    row0 = row0 + " <button id='edit' name='edit' class='btn-primary' type='button' onclick='EditRow(" + JData["CLASS_NO"][i] + "," + JData["SERIALNO"][i] + "," + 2 + ")' title='修改'>修改</button>" ;
                  }
                  row0 = row0 + "</td></tr>";

                }
                row0 = row0 + "</tbody></table>";
                $('#_content').append(row0);
              }
            }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
        });

        if($('#cyear').val() == null)
        {
            //填寫表單部分
            //科目
            $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'qry_subject', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val() },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                $('#subject_name').empty();

                if (JData.error_code)
                    toastr["error"](JData.error_message);
                else
                {
                    var row0 = "<option selected disabled value = '' class='text-hide'>請選擇科目</option>";

                    for(var i = 0 ; i < JData["SCR_SELCODE"].length ; i++)
                    {
                        row0 = row0 + "<option class_no = '' value='" + JData["SCR_SELCODE"][i] + "'>" + JData["SCR_SELCODE"][i] + JData["SUB_NAME"][i] + "</option>";
                    }

                    $('#subject_name').append(row0);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
            });

            //上課班別
            $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'qry_class_id', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val() },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                $('#class_name').empty();

                if (JData.error_code)
                    toastr["error"](JData.error_message);
                else
                {
                    $('#class_name').append(JData);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
            });

            //原上課日期及調補課日期
            $.ajax({
            url: 'ajax/class_year_2_ajax.php',
            data: { oper: 'qry_dates'},
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                row0 = "<option selected disabled value = '' class='text-hide'>請選擇年份</option>";
                $ ('#cyear').append(row0);
                for (var i = JData["cyear"] - 1 ; i <= JData["cyear"] + 1 ; i++)
                {
                    if (i == JData["cyear"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#cyear').append(row);
                }

                row0 = "<option selected disabled value = '' class='text-hide'>請選擇月份</option>";
                $ ('#cmonth').append(row0);
                for (var i = 1 ; i <= 12 ; i++)
                {
                    if (i == JData["cmonth"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#cmonth').append(row);
                }

                row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
                $ ('#cday').append(row0);
                var days = new Date( parseInt(JData["cyear"])+1911,JData["cmonth"],0).getDate();
                for (var i = 1 ; i <= days ; i++)
                {
                    if (i == JData["cday"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#cday').append(row);
                }

                row0 = "<option selected disabled value = '' class='text-hide'>請選擇年份</option>";
                $ ('#dyear').append(row0);
                for (var i = JData["dyear"] - 1 ; i <= JData["dyear"] + 1 ; i++)
                {
                    if (i == JData["dyear"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#dyear').append(row);
                }

                row0 = "<option selected disabled value = '' class='text-hide'>請選擇月份</option>";
                $ ('#dmonth').append(row0);
                for (var i = 1 ; i <= 12 ; i++)
                {
                    if (i == JData["dmonth"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#dmonth').append(row);
                }

                row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
                $ ('#dday').append(row0);
                var days = new Date( parseInt(JData["cyear"])+1911 ,JData["cmonth"],0).getDate();
                for (var i = 1 ; i <= days ; i++)
                {
                    if (i == JData["dday"])
                        row = "<option value=" +i+ " selected>" + i + "</option>";
                    else
                        row = "<option value=" +i+ ">" + i + " </option>";
                    $ ('#dday').append(row);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);
            alert("error!!");}
            });
            $('#cmonth,#cyear').change(
                function(e) {
                    if ($(':selected', this).val() !== null) {
                            GetDays('c');
                        }
                }
            );
            $('#dmonth,#dyear').change(
                function(e) {
                    if ($(':selected', this).val() !== null) {
                            GetDays('d');
                        }
                }
            );
        }
    }

}

function GetDays(type)
{
    if(type == 'c')
    {
        var days = new Date( parseInt($('#cyear').val())+1911, $('#cmonth').val(), 0).getDate();
        row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
        $ ('#cday').empty();
        $ ('#cday').append(row0);
        for (var i = 1 ; i <= days ; i++)
        {
            row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#cday').append(row);
        }
    }
    else
    {
        var days = new Date( parseInt($('#dyear').val())+1911, $('#dmonth').val(), 0).getDate();
        row0 = "<option selected disabled value = '' class='text-hide'>請選擇日期</option>";
        $ ('#dday').empty();
        $ ('#dday').append(row0);
        for (var i = 1 ; i <= days ; i++)
        {
            row = "<option value=" +i+ ">" + i + " </option>";
            $ ('#dday').append(row);
        }
    }

}

function DeleteRow(classno, serialno)
{
    if(confirm("確定要刪除嗎?"))
    $.ajax({
        url: 'ajax/class_year_2_ajax.php',
        data: { oper: 'del', serialno: serialno, class_no: classno},
        type: 'POST',
        dataType: "json",
        success: function(JData) {

            if (JData.error_code)
                toastr["error"](JData.error_message);
            else
            {
                if(JData.length == 7)
                    toastr["success"](JData);
                else
                    toastr["error"](JData);

                CRUD(0);
            }

        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}

function Insert()
{

    var class_year = $('#class_year').val();
    var class_acadm = $('#class_acadm').val();

    $.ajax({
        url: 'ajax/class_year_2_ajax.php',
        data: { oper: 'new', class_year: $('#class_year').val(), class_acadm: $('#class_acadm').val(), class_subject: $('#subject_name').val(),
                class_name: $('#class_name').text(), class_section2: $('#class_section2').val(), class_room: $('#class_room').val(),
                class_memo: $('#class_memo').val(), cyear: $('#cyear').val(), cmonth: $('#cmonth').val(), cday: $('#cday').val(),
                dyear: $('#dyear').val(), dmonth: $('#dmonth').val(), dday: $('#dday').val() },
        type: 'POST',
        dataType: "json",
        success: function(JData) {

            if (JData.error_code)
                toastr["error"](JData.error_message);
            else
            {
                if(JData.length == 7)
                    toastr["success"](JData);
                else
                    toastr["error"](JData);

                rec_reload(class_year,class_acadm);
            }

        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}

function rec_reload(re_class_year, re_class_acadm)
{

    $.ajax({
        url: 'ajax/class_year_2_ajax.php',
        data: { oper: 'qry_record', class_year: re_class_year, class_acadm: re_class_acadm },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
          $('#_content').empty();

          if (JData.error_code)
              toastr["error"](JData.error_message);
          else{
          if (JData.length == "0"){
              var row_part_new = "<center style='color:red'>無任何記錄。</center>";
              $('#_content').append(row_part_new);

          }else {
            var row0 ="";
            row0 = row0 + "<table class='table table-bordered col-md-8'><tbody><tr>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>上課班別</td>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>科目名稱</td>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>原上課時間</td>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>補課時間</td>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>補課教室</td>";
            row0 = row0 + "<td class='td1' style='text-align:center;'>補課節次</td>";
            if(JData["SERIALNO"].length != 0)
                row0 = row0 + "<td class='td1' style='text-align:center;'>刪除或修改申請單</td>";
            row0 = row0 + "</tr>";

            for(var i = 0 ; i < JData["CLASS_NAME"].length ; i++){

              row0 = row0 + "<tr><td  style='text-align:center;'>" ;
              row0 = row0 + JData["CLASS_NAME"][i];
              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              row0 = row0 + JData["CLASS_SUBJECT"][i];
              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              row0 = row0 + JData["CLASS_DATE"][i];
              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              row0 = row0 + JData["CLASS_DATE2"][i];
              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              row0 = row0 + JData["CLASS_ROOM"][i];

              row0 = row0 + "</td><td  style='text-align:center;'>" ;
              row0 = row0 + JData["CLASS_SECTION2"][i];
              if( JData["SERIALNO"][i] != null )
              {
                row0 = row0 + "</td><td  style='text-align:center;'>" ;
                row0 = row0 + "<button id='delete' name='delete' class='btn-danger' type='button' onclick='DeleteRow(" + JData["CLASS_NO"][i] + "," + JData["SERIALNO"][i] + ")'; title='刪除'>刪除</button>" ;
                row0 = row0 + " <button id='edit' name='edit' class='btn-primary' type='button' onclick='EditRow(" + JData["CLASS_NO"][i] + "," + JData["SERIALNO"][i] + "," + 2 + ")' title='修改'>修改</button>" ;
              }
              row0 = row0 + "</td></tr>";

            }
            row0 = row0 + "</tbody></table>";
            $('#_content').append(row0);
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

// function Send()
// {
//   var oyear=$ ('#ocyear').val();
//   var omonth=$ ('#ocmonth').val();
//   var oday=$ ('#ocday').val();

//   var cyear=$ ('#ccyear').val();
//   var cmonth=$ ('#ccmonth').val();
//   var cday=$ ('#ccday').val();

//   var class_subject=$('#subject-name').val();
//   var class_section2=$ ('#class_section2').val();
//   var class_room=$('#class_room').val();
//   var class_memo=$('#class_memo').val();

//   var class_name=$('#class-name').val();

//   var class_year=$ ('#qry_class_year').val();
//   var class_acadm=$ ('#qry_acadm').val();

//   $.ajax({
//       url: 'ajax/class_add_ajax.php',
//       data: { oper: 'send' ,classsubject:class_subject,byear:oyear,bmonth:omonth,bday:oday
//              ,eyear:cyear,emonth:cmonth,eday:cday,classsection2:class_section2,serialno:serailnoval,class_no:classnoval,class_room:class_room,class_memo:class_memo,class_year:class_year,class_acadm:class_acadm},
//       type: 'POST',
//       dataType: "json",
//       success: function() {
//         toastr["success"]("處理完成!!");
//         $("#ChangeModal2").modal("hide");
//       },
//       error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
//     });
// }
// function CheckData()
// {
//   var oyear=$ ('#ocyear').val();
//   var omonth=$ ('#ocmonth').val();
//   var oday=$ ('#ocday').val();

//   var cyear=$ ('#ccyear').val();
//   var cmonth=$ ('#ccmonth').val();
//   var cday=$ ('#ccday').val();

//   var class_subject=$('#subject-name').val();
//   var class_section2=$ ('#class_section2').val();
//   var class_room=$('#class_room').val();
//   var class_memo=$('#class_memo').val();

//   var class_name=$('#class-name').val();

//   var class_year=$ ('#qry_class_year').val();
//   var class_acadm=$ ('#qry_acadm').val();

//   $.ajax({
//       url: 'ajax/class_add_ajax.php',
//       data: { oper: 'ch' },
//       type: 'POST',
//       dataType: "json",
//       success: function() {
//         if(class_section2=="")
//         {
//           toastr["error"]("補課節次未填寫!");
//         }else if(class_room=="")
//           toastr["error"]("補課教室未填寫!");
//         else
//         {

//           Send();
//         }

//       },
//       error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
//     });
// }
// function closeM()
// {
//   $("#ChangeModal2").modal("hide");
// }
