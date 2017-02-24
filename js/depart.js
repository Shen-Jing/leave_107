var arr_dept;
var dept_to_short;
var date = new Date();
var year = date.getFullYear() - 1911;
var ad_year = date.getFullYear();
var month = date.getMonth() + 1;
$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });

        // 改變滑鼠游標樣式
        $('#container tbody').on('mouseover', 'tr', function() {
            this.style.cursor = 'pointer';
        });

        // 選擇年份後，即可顯示資料
        $('#qry_year').change(
            function(e) {
                if ($(':selected', this).val() !== ''){
                    CRUD(0);
                }
            }
        )

        // 查詢月份若有改變也要query
        $('#qry_month').change(
            function(e) {
                // 但是必須有選取年份
                if ($(':selected', this).val() != null && $('#qry_year').val() != null){
                    CRUD(0);
                }
            }
        )

        // 查詢單位若有改變也要query
        $('#qry_dept').change(
            function(e) {
                // 但是必須有選取年份
                if ($(':selected', this).val() != null && $('#qry_year').val() != null){
                    CRUD(0);
                }
            }
        )
    });

function CRUD(oper, empl_no, over_date) {
    $.ajax({
        url: 'ajax/depart_ajax.php',
        data: {
            year: $('#qry_year').val(),
            month: $('#qry_month').val(),
            dept: $('#qry_dept').val()
        },
        type: 'POST',
        dataType: "json",
        success: function(JData) {
            if (JData.error_code)
                toastr["error"](JData.error_message);
            else {
                if (oper == "0") { //查詢
                    $('#_content').empty();
                    data_length = JData.EMPL_CHN_NAME.length;
                    if (data_length == 0) {
                        $('#_content').append("<tr><td colspan='10'>目前尚無資料</td></tr>");
                    }
                    else {
                        $('#_content').empty();
                        for (var i = 0; i < data_length; i++) {
                            var row = "<tr>";
                            // 縮寫單位（系統開發組）
                            row = row + "<td>" + JData.DEPT_SHORT_NAME[i] + "</td>";
                            // 姓名（李_朗）
                            row = row + "<td>" + JData.EMPL_CHN_NAME[i] + "</td>";
                            // 假別`（出差）
                            row = row + "<td>" + JData.CODE_CHN_ITEM[i] + "</td>";
                            // 起始日（1050131）
                            row = row + "<td>" + JData.POVDATEB[i] + "</td>";
                            // 終止日（1050204）
                            row = row + "<td>" + JData.POVDATEE[i] + "</td>";
                            // 起始（8）
                            if (JData.POVTIMEB[i].length > 2){
                              JData.POVTIMEB[i] =
                              JData.POVTIMEB[i].substr(0, 2) + ":" +
                              JData.POVTIMEB[i].substr(2);
                            }
                            row = row + "<td>" + JData.POVTIMEB[i] + "</td>";
                            // 終止
                            if (JData.POVTIMEE[i].length > 2){
                              JData.POVTIMEE[i] =
                              JData.POVTIMEE[i].substr(0, 2) + ":" +
                              JData.POVTIMEE[i].substr(2);
                            }
                            row = row + "<td>" + JData.POVTIMEE[i] + "</td>";
                            // 總時數
                            row = row + "<td>" + JData.POVDAYS[i] + "天" + JData.POVHOURS[i] + "時</td>";
                            // 代理簽
                            if (JData.AGENTSIGND[i] == '')
                              row = row + "<td>-</td>";
                            else
                              row = row + "<td>" + JData.AGENTSIGND[i] + "</td>";
                            // 直屬簽
                            if (JData.ONESIGND[i] == '')
                              row = row + "<td>-</td>";
                            else
                              row = row + "<td>" + JData.ONESIGND[i] + "</td>";
                            // 單位簽
                            if (JData.TWOSIGND[i] == '')
                              row = row + "<td>-</td>";
                            else
                              row = row + "<td>" + JData.TWOSIGND[i] + "</td>";
                            // 代理人
                            if (JData.AGENT_NAME[i] == null)
                              row = row + "<td>-</td>";
                            else
                              row = row + "<td>" + JData.AGENT_NAME[i] + "</td>";
                            // 處理狀況
                            var condition = '-';
                            if (JData.CONDITION[i]=='0')
                              condition='簽核中';
                            else  if (JData.CONDITION[i]=='1')
                              condition='簽核完成';
                            else  if (JData.CONDITION[i]=='2')
                              condition='被退中';
                            row = row + "<td>" + condition + "</td>";
                            row = row + "</tr>";
                            $('#_content').append(row);
                        }
                    }
                }
            }
        },
        beforeSend: function() {
            $('#loading').show();
        },
        complete: function() {
            $('#loading').hide();
        },
        error: function(xhr, ajaxOptions, thrownError) {
        }
    });
}
