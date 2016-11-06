
  alert("Js Connect! 1");
  function p_menu_onChange()
  {
      var yycho ;
      var yyval;
      var mmcho ;
      var mmval;
      yycho =document.form1.p_menu.selectedIndex;
      yyval=document.form1.p_menu.options[yycho].value;
      alert("p_menu connect!!");


      $.ajax({
          url: 'ajax/search_detail_ajax.php',
          data: { oper: 'detail_year',p_menu: yyval },
          type: 'POST',
          dataType: "json",
          success: function(JData) {
            alert("success");
              var row0 = "<option selected disabled class='text-hide'>請選擇年份</option>";
              $('#detail_year').append(row0);
              for (var i = 99; i <= parseInt( JData ) ; i++) {
                  if (i == parseInt( JData ))
                      var row = "<option value=" +i+ " selected>" + i + "</option>";
                  else
                      var row = "<option value=" +i+ ">" + i + " </option>";
                  $('#detail_year').append(row);
              }
              CRUD(0);//首次進入頁面query
          },
          error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
      });



  }
alert("Js Connect!");
