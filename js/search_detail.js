$(
function year_control()
  {
      alert("test");
      var yycho ;
      var yyval;
      yycho =document.form1.p_menu.selectedIndex;
      //yyval=document.form1.p_menu.options[yycho].value;
      alert("p_menu connect!!");
      $("body").tooltip({
          selector: "[title]"
      });

      $.ajax({
          url: 'ajax/search_detail_ajax.php',
          data: { oper: 'p_menu',
                  year: yyval},
          type: 'POST',
          dataType: "json",
          success: function(JData) {

              //選擇年分
              var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
              $('#p_menu').append(row0);
              //alert("year==".JData["year"][0]);
              for (var i = 99  ; i <= parseInt( JData["year"][0] ) + 1 ; i++) {
                  if (i == parseInt( JData["year"][0] ))
                      var row = "<option value=" + i + " selected>" + i + "</option>";
                  else
                      var row = "<option value=" + i + ">" + i + " </option>";
                  $('#p_menu').append(row);
              }


          },
          error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);
          alert("error!!");}
      });



  }
);
