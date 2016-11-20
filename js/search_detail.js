$(
function year_control()
  {
    var yycho ;
    var yyval;
    yycho =document.form1.p_menu.selectedIndex;
    //yyval=document.form1.p_menu.options[yycho].value;

      $("body").tooltip({
          selector: "[title]"
      });

      $.ajax({
          url: 'ajax/search_detail_ajax.php',
          data: { oper: 'p_menu'},
          type: 'POST',
          dataType: "json",
          success: function(JData) {
            $('#empl_no').append(JData["empl_no"][0]);
            $('#empl_name').append(JData["empl_name"][0]);


              //選擇年分
              var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
              $('#p_menu').append(row0);
              //alert("year==".JData["year"][0]);
              for (var i = 99  ; i <= parseInt( JData["year"][0] ); i++) {
                  if (i == parseInt( JData["year"][0] ))
                      var row = "<option value=" + i + " selected>" + i + "</option>";
                  else
                      var row = "<option value=" + i + ">" + i + " </option>";
                  $('#p_menu').append(row);
              }
              CRUD(0);//首次進入頁面query

          },
          error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);
          alert("error!!");}
      });

      $('#p_menu').change( // 抓取區域選完的資料
          function(e) {
              if ($(':selected', this).val() !== '') {
                  CRUD(0); //query
              }
          }
      );



  }
);

function CRUD(oper) {

    var yycho ;
    var yyval;
    yycho =document.form1.p_menu.selectedIndex;
    yyval=document.form1.p_menu.options[yycho].value;

    $.ajax({
        url: 'ajax/search_detail_ajax.php',
        data: { oper: oper ,year: yyval},
        type: 'POST',
        dataType: "json",
        success: function(JData) {
          $('#start_end').empty();
          $('#start_end').append("日期:"+yyval+"年1月1日~"+yyval+"年12月31日");
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else{

                    //$('#Table_Detail').empty();
                    $('#Table_Detail').empty();
                    if (JData.COUNT==0){
                        var row_part_new = "<center style='color:red'>您目前無任何記錄。</center><br>";
                        $('#Table_Detail').append(row_part_new);

                    }

                    else{

                            var row0 ="";
                            row0=row0+"<tr style=\"font-weight:bold\">";
                            row0=row0+"<th>日期</th>";
                            row0=row0+"<th>假別天數</th>";
                            row0=row0+"<th>日期</th>";
                            row0=row0+"<th>假別天數</th>";
                            row0=row0+"<th>日期</th>";
                            row0=row0+"<th>假別天數</th>";
                            row0=row0+"</tr>";
                            var col=1;

                            for(var i=0;i<JData.POCARD.length;i++){

                              var pocard = JData.POCARD[i];
                              var povtype= JData.CODE_CHN_ITEM[i];
                              var povdateB = JData.POVDATEB[i];
                              var povhours = JData.POVHOURS[i];
                              var povdays  = JData.POVDAYS[i];
                              var condition  = JData.CONDITION[i];
                              if(col==1)
                                row0=row0+"<tr>";

                              var pohdaye=0;
                              var pohoure=0;

                                  if (pohdaye=='') pohdaye=0;
                                  if (pohoure=='') pohoure=0;
                                  if (condition=='1')
                                row0=row0+"<th>" + povdateB + "</th><th>" + povtype + " " + povdays + "日" + povhours + "時" + "</th>";
                              else
                                row0=row0+"<th><font color=\"red\">" + povdateB + "</font></th><th>" + povtype + " " + povdays + "日" + povhours + "時" + "</th>";

                              col++;
                              if(col==4)
                              {
                                col=1;
                                row0=row0 + "</tr>";
                              }
                            }

                            $('#Table_Detail').append(row0);
                    }//else
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
    });
}
