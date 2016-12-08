$(
function year_control()
  {

//alert("in Func1!");
      $("body").tooltip({
          selector: "[title]"
      });

      $.ajax({
          url: 'ajax/class_add_ajax.php',
          data: { oper: 'qry_year'},
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

          },
          error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);
          alert("error!!");}
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

function CRUD(oper) {

  var yyval, mmval;
  yyval = $ ('#qry_year').val();
  mmval = $ ('#qry_month').val();
  //alert("in Func!");
  $.ajax({
      url: 'ajax/class_add_ajax.php',
      data: { oper: oper ,p_year: yyval,p_month:mmval},
      type: 'POST',
      dataType: "json",
      success: function(JData) {
        $('#_content').empty();
        if (oper == "0") {
        if (JData.error_code)
            toastr["error"](JData.error_message);
        else{
        if (JData.COUNT=="0"){
            var row_part_new = "<center style='color:red'>本月無任何記錄。</center><br>";
            $('#_content').append(row_part_new);

        }else {
          var row0 ="";
          row0 = row0 + "<table class='table table-bordered col-md-8'><tbody><tr>";
          row0 = row0 + "<td class='td1' style='text-align:center;'>姓名</td>";
          row0 = row0 + "<td class='td1' style='text-align:center;'>假別</td>";
          row0 = row0 + "<td class='td1' style='text-align:center;'>起始日</td>";
          row0 = row0 + "<td class='td1' style='text-align:center;'>終止日</td>";
          row0 = row0 + "<td class='td1' style='text-align:center;'>起始時</td>";
          row0 = row0 + "<td class='td1' style='text-align:center;'>終止時</td>";
          row0 = row0 + "<td class='td1' style='text-align:center;'>天數</td>";
          row0 = row0 + "<td class='td1' style='text-align:center;'>補填或修改申請單</td></tr>";


          for(var i=0;i<JData.EMPL_CHN_NAME.length;i++){

            row0 = row0 + "<tr><td  style='text-align:center;'>" ;
            row0 = row0 + JData.EMPL_CHN_NAME[i];
            row0 = row0 + "</td><td  style='text-align:center;'>" ;
            row0 = row0 + JData.CODE_CHN_ITEM[i];
            row0 = row0 + "</td><td  style='text-align:center;'>" ;
            row0 = row0 + JData.POVDATEB[i];
            row0 = row0 + "</td><td  style='text-align:center;'>" ;
            row0 = row0 + JData.POVDATEE[i];
            row0 = row0 + "</td><td  style='text-align:center;'>" ;
            row0 = row0 + JData.POVTIMEB[i];
            row0 = row0 + "</td><td  style='text-align:center;'>" ;
            row0 = row0 + JData.POVTIMEE[i];
            row0 = row0 + "</td><td  style='text-align:center;'>" ;
            row0 = row0 + JData.POVDAYS[i];
            row0 = row0 + "天";
            row0 = row0 + JData.POVHOURS[i];
            row0 = row0 + "時";
            row0 = row0 + "</td><td  style='text-align:center;'>" ;
            row0 = row0 + "<button type='button'>修改</button>" ;
            row0 = row0 + "</td></tr>";




          }
          row0 = row0 + "</tbody></table>";
          $('#_content').append(row0);
        }
      }
}
      },
      error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
  });

}
