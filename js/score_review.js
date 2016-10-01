var arr_dept;
$( // 表示網頁完成後才會載入
    function() {
        $('#qry_student_id').focus();

        $('#qry_student_id').keyup( //輸入准考證號後
            function(e) {
                if ($('#qry_student_id').val().length >= 8) {
                    $.ajax({
                        url: 'ajax/score_review_ajax.php',
                        data: {
                            id: $('#qry_student_id').val(),
                            oper: 'query'
                        },
                        type: 'POST',
                        dataType: "json",
                        success: function(JData) {
                            if (JData.error_code)
                                toastr["error"](JData.error_message);
                            else {
                                $('#_content').empty();
                                for (var i = 0; i < JData.SUBJECT_ID.length; i++) {
                                    var row = "<tr>";
                                    row = row + "<td class='col-xs-2'>" + JData.SUBJECT_ID[i] + "</td>";
                                    row = row + "<td class='col-xs-2'>" + JData.SUBJECT_NAME[i] + "</td>";
                                    row = row + "<td class='col-xs-1'>" + JData.RESULT[i] + "</td>";
                                    row = row + "<td class='col-xs-2'>" + JData.BAGID[i] + "</td>";
                                    row = row + "<td class='col-xs-2'>" + JData.SECONDORDER[i] + "</td>";
                                    row = row + "<td class='col-xs-1'>" + JData.CLASSROOM_ID[i] + "</td>";
                                    row = row + "<td class='col-xs-2'>" + JData.BAG_NO[i] + "</td>";
                                    row = row + "</tr>";
                                    $('#_content').append(row);
                                }
                            }
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {}
                    });
                }
            }
        );


    });
