$( // 表示網頁完成後才會載入
    function() {
        $.ajax({
            url: 'ajax/barcode_check_ajax.php',
            data: { oper: 'qry_campus' },
            type: 'POST',
            dataType: "json",
            success: function(JData) {
                var row0 = "<option selected disabled class='text-hide'>請選擇學院</option>";
                $('#qry_campus').append(row0);
                for (var i = 0; i < JData.ID.length; i++) {
                    var row = "<option value=" + JData.ID[i] + ">" + JData.NAME[i] + "</option>";
                    $('#qry_campus').append(row);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {}
        });


        $('#qry_campus').change( //選擇學院後
            function(e) {
                if ($(':selected', this).val() !== '') {
                    $.ajax({
                        url: 'ajax/barcode_check_ajax.php',
                        data: {
                            oper: 'qry_dept',
                            campus_id: $('#qry_campus').val()
                        },
                        type: 'POST',
                        dataType: "json",
                        success: function(JData) {
                            $('#qry_dept').empty();
                            var row0 = "<option selected disabled class='text-hide'>請選擇系所</option>";
                            $('#qry_dept').append(row0);
                            for (var i = 0; i < JData.ID.length; i++) {
                                var row = "<option value=" + JData.ID[i] + ">" + JData.NAME[i] + "</option>";
                                $('#qry_dept').append(row);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {}
                    });
                }
            }
        );

        // $('#qry_dept').change( //選擇系所後
        //     function(e) {
        //         if ($(':selected', this).val() !== '') {
        //             CRUD(0); //query
        //         }
        //     }
        // );
        $('#qry_dept').change(
            function(e) {
                if ($(':selected', this).val() > 0) {
                    var sel = $(':selected', this).val();
                    GetBarcode(sel); // 取的條碼
                }
            }
        );
    }
);

var barcode_data; // 條碼資料
var barcode_now; // 現在第Ｎ筆

function GetBarcode(sel) { // 取得條碼
    $.ajax({
        url: 'ajax/barcode_check_ajax.php',
        data: {
            oper: 'qry_barcode',
            id: sel,
        },
        type: 'POST',
        dataType: "json",
        success: function(Jdata) {
            $('#_content').empty(); // 清空條碼表格
            for (var i = 0; i < Jdata.MAKENUMBER.length; i++) { // 加入條碼資料
                var row = "<tr id=\"" + Jdata.MAKENUMBER[i] + "\"><td>" + Jdata.MAKENUMBER[i] + "</td><td>" + Jdata.PERSON_STUDENT_ID[i] + "</td><td>" + Jdata.SUBJECT_ID[i] + "</td><td>" + Jdata.NAME[i] + "</td></tr>";
                $('#_content').append(row);
            }
            $('#Binput').focus();
            barcode_data = Jdata; // 存下 條碼資料
            barcode_now = 0; // 歸零目前條碼
            $('#Binput').on('keyup keydown keypress input', // 偵測輸入
                function(e) {
                    if ($('#Binput').val().length >= barcode_data.MAKENUMBER[0].length) {
                        var checking = $('#Binput').val();
                        CheckBarcode(checking); // 檢查條碼
                    }
                }
            );
        },
        error: function(xhr, ajaxOptions, thrownError) {}
    });
}

function CheckBarcode(checking) { // 檢查條碼
    if (checking == barcode_data.MAKENUMBER[barcode_now]) { // 條碼正確
        toastr["success"](checking + '：正確');
        var Btr = "#" + barcode_data.MAKENUMBER[barcode_now++]; // 設定ＩＤ 移動目前條碼指標
        $(Btr).removeClass("danger"); // 移除錯誤樣式
        $(Btr).addClass("success"); // 新增正確樣式
    } else {
        message(checking + '：錯誤', "danger", 5000);
        var errBtr = "#" + barcode_data.MAKENUMBER[barcode_now]; // 設定ＩＤ
        $(errBtr).addClass("danger"); // 新增錯誤樣式
        alert(checking + '：錯誤');
    }

    if (barcode_now == barcode_data.MAKENUMBER.length) {
        alert("條碼檢查作業完成！！");
        $('#Binput').val(''); // 清空輸入欄位
    } else {
        $('#Binput').val(''); // 清空輸入欄位
        $('#Binput').focus();
        $('#Binput').on('keyup keydown keypress input',
            function(e) {
                if ($('#Binput').val().length >= barcode_data.MAKENUMBER[0].length) {
                    var checking = $('#Binput').val();
                    CheckBarcode(checking); // 檢查條碼
                }
            }
        );
    }
}
