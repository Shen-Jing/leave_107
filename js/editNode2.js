
$(function()
{
  var options = {
      currElCss: {'background-color':'green', 'color':'#000'},
      placeholderCss: {'background-color': '#ff8'},
      hintCss: {'background-color':'#bbf'},
      isAllowed: function(cEl, hint, target)
      {
        if (cEl.parent().index('ul') == -1)
        {
          alert('不能拖曳父元素R~');
          return false;
        }
        if(hint.parents('li').first().data('module') === 'c' && cEl.data('module') !== 'c')
        {
          hint.css('background-color', '#ff9999');
          return false;
        }
        else
        {
          hint.css('background-color', '#99ff99');
          return true;
        }
      },
      opener: {
         active: true,
         close: 'http://www.internetke.com/jsEffects/2015080322/images/Remove2.png',
         open: 'http://www.internetke.com/jsEffects/2015080322/images/Add2.png',
         openerCss: {
           'display': 'inline-block',
           'width': '18px',
           'height': '18px',
           'float': 'left',
           'margin-left': '-35px',
           'margin-right': '5px',
           'background-position': 'center center',
           'background-repeat': 'no-repeat'
         },
         openerClass: ''
      },
      onDragStart: function(e, el){

                  alert('abandn');
          e.preventDefault();
        if(el.has('ul'))
          alert('abandn');
      },
      complete: function(cEl)
      {
        setTimeout(function (){
          var o_id = cEl.attr('id');
          var n_id = cEl.parent().parent().attr('id');
          var index = $('#' + n_id + ' > ul > li').index(cEl) + 1;
          if (index < 10)
            n_id = n_id + '0' + index;
          else
            n_id = n_id + index;
          $.ajax({
            url: '/ajax/updateNode.php',
            type: 'POST',
            data: {o_id: o_id, n_id: n_id},
            success: function(result){
              alert('success');
              console.log(result);
              cEl.attr({
                id: n_id
              });
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
              console.log(JSON.stringify(jqXHR));
              console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
          });
        }, 500);
      }
    },

    sWrapper = $('#settingsWrapper');

  $('#sTree2, #sTree').sortableLists(options);

  $("#add-dialog").dialog({
        autoOpen: false,
        show: "blind",
        hide: "fade",
        buttons: {
            "確定新增": function() {
              $type = '';
              if ($('#add-type').prop("checked"))
                $type = '1';
              //$.post('../ajax/insertNode.php', {id: $('#id').val(), name: $('#name').val(), url: $('#url').val(), type: $('#type').val(), img: $('#img').val()}, function(result){alert(result)});
              $.ajax({
                url: '/ajax/insertNode.php',
                type: 'POST',
                data: {id: $('#add-id').val(), name: $('#add-name').val(), url: $('#add-url').val(), type: $type, img: $('#add-img').val()},
                success: function(result){
                  alert('success');
                  console.log(result);
                  if ($('#add-id').val().length > 2)
                    $('#' + $('#add-id').val().slice(0, -2) + ' > ul').append("<li id='" + $('#add-id').val() + "'>\r\n<div>" + $('#add-name').val() + "</div>");
                  else
                    $('#sTree2').append("<li id='" + $('#add-id').val() + "'>\r\n<div>" + $('#add-name').val() + "</div>");
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                  console.log(JSON.stringify(jqXHR));
                  console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                  if ($('#add-id').val().length > 2)
                    $('#' + $('#add-id').val().slice(0, -2) + ' > ul').append("<li id='" + $('#add-id').val() + "'>\r\n<div>" + $('#add-name').val() + "</div>");
                  else
                    $('#sTree2').append("<li id='" + $('#add-id').val() + "'>\r\n<div>" + $('#add-name').val() + "</div>");
                }
              });
              $(this).dialog("close");
            },
            "取消": function() {
              $(this).dialog("close");
            }
        }
    });
    $("#add-opener").click(function() {
        $("#add-dialog").dialog("open");
        return false;
    });

    $("#edit-dialog").dialog({
        autoOpen: false,
        show: "fold",
        hide: "fade",
        buttons: {
            "確定編輯": function() {
              $type = '';
              if ($('#edit-type').prop("checked"))
                $type = '1';
              //$.post('../ajax/insertNode.php', {id: $('#id').val(), name: $('#name').val(), url: $('#url').val(), type: $('#type').val(), img: $('#img').val()}, function(result){alert(result)});
              $.ajax({
                url: '/ajax/updateNode.php',
                type: 'POST',
                data: {id: $('#edit-id').val(), name: $('#edit-name').val(), url: $('#edit-url').val(), type: $type, img: $('#edit-img').val()},
                success: function(result){
                  alert('success');
                  console.log(result);
                  $('#' + $('#edit-id').val() + ' > div').html($('#edit-name').val());
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                  console.log(JSON.stringify(jqXHR));
                  console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                  $('#' + $('#edit-id').val() + ' > div').html($('#edit-name').val());
                }
              });
              $(this).dialog("close");
            },
            "取消": function() {
              $(this).dialog("close");
            }
        }
    });
    $(".edit-opener").click(function() {
        $("#edit-dialog").dialog("open");
        return false;
    });

    $('#delete').click(function(event) {
      var id = prompt("程式 ID：", "");
      if (id)
      {
        $.ajax({
          url: '/ajax/deleteNode.php',
          type: 'POST',
          data: {id: id},
          success: function(result){
            alert('success');
            console.log(result);
            $('#' + id).remove();
          },
          error: function(jqXHR, textStatus, errorThrown)
          {
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            $('#' + id).remove();
          }
        });
      }
    });
});
