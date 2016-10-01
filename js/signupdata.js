var arr_dept;
$( // 表示網頁完成後才會載入
    function() {
        $("body").tooltip({
            selector: "[title]"
        });
        var tables = $('#example').DataTable({
            "ajax": {
                url: 'ajax/signupdata_ajax.php',
                data: {
                    oper: 9
                },
                type: 'POST',
                dataType: 'json'
            },
            "scrollX": true,
            "scrollY": "550px",
            "scrollCollapse": true, //當筆數小於scrillY高度時,自動縮小
            "displayLength": 10,
            "paginate": true, //是否分頁
            "lengthChange": true,
            "columns": [
                { "name": "functions" },
                { "name": "name" },
                { "name": "id" },
                { "name": "sex" },
                { "name": "dept_name" },
                { "name": "group_name" },
                { "name": "orastatus_id" },
                { "name": "birthday" },
                { "name": "email" },
                { "name": "zip" },
                { "name": "address" },
                { "name": "zip_o" },
                { "name": "address_o" },
                { "name": "tel_h" },
                { "name": "tel_o" },
                { "name": "tel_m" },
                { "name": "cripple_type" },
                { "name": "prove_type" },
                { "name": "signup_sn" },
                { "name": "subject_id" },
                { "name": "lock_up" },
                { "name": "ac_school_name" },
                { "name": "ac_dept_name" },
                { "name": "ac_date" }
            ],
            "columnDefs": [{
                "targets": 0, //0:第一欄;-1:最後一欄;-2:倒數第二欄
                "data": null,
                "defaultContent": "<button id='detail' class='btn-default' type='button' title='詳細資料'><i class='fa fa-info'></i></button>&nbsp;&nbsp;<button id='editrow' class='btn-primary' type='button' title='編輯'><i class='fa fa-edit'></i></button>&nbsp;&nbsp;<button id='delrow' class='btn-danger' type='button' title='刪除'><i class='fa fa-trash-o'></i></button>"
            }, {
                "targets": 6, //代碼(orastatus_id)
                "visible": false,
                "searchable": false
            }, {
                "targets": 17, //學歷證明(prove_type)
                "visible": false,
                "searchable": false
            }, {
                "targets": 19, //選考科目(subject_id)
                "visible": false,
                "searchable": false
            }, {
                "targets": 20, //確認(lock_up)
                "visible": false,
                "searchable": false
            }],
            "order": [
                [4, "asc"],
                [2, "desc"]
            ],
            "stateSave": false,
            "processing": false,
            "serverSide": false,
            "dom": 'Blfrtip',
            "buttons": [{
                    text: "<i class='fa fa-plus'> 新 增 </i>",
                    className: 'blue', //no-use
                    action: function(e, dt, node, config) {
                        $(":input[name='id']").removeAttr("disabled");
                        $(":input[name='sex']").removeAttr("disabled");
                        $(":input[name='dept_name']").removeAttr("disabled");
                        $(":input[name='group_name']").removeAttr("disabled");
                        $(":input[name='signup_sn']").removeAttr("disabled");
                        var fields = $("#add-form").serializeArray(); //取得HTML表單每個欄位名稱
                        jQuery.each(fields, function(i, field) {
                            $(":input[name='" + field.name + "']").val('');
                        });
                        $("#modal-form").modal("show"); //弹出框show
                    }
                }, {
                    extend: 'excel',
                    text: '匯出此頁(Excel)',
                    filename: 'data',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                }, 'excel'] //csv,pdf,excel,print,colvis
        });

        //刪除
        $('#example tbody').on('click', 'button#delrow', function() {
            var data = tables.row($(this).parents('tr')).data();
            if (!confirm("是否確定要刪除此筆資料 ( " + data[1] + " ) ?")) return false;
            $.ajax({
                url: 'ajax/signupdata_ajax.php',
                data: {
                    oper: 3,
                    id: data[2],
                    signup_sn: data[18]
                },
                type: 'POST',
                dataType: 'json',
                success: function(JData) {
                    console.log('in');
                    if (JData.error_code)
                    //toastr["error"](JData.error_message);
                        message(JData.error_message, "danger", 5000);
                    else {
                        tables.ajax.reload();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {}
            });
        });

        //編輯
        $('#example tbody').on('click', 'button#editrow', function() {
            var data = tables.row($(this).parents('tr')).data();
            var fields = $("#add-form").serializeArray(); //取得HTML表單每個欄位名稱
            var columns = tables.settings().init().columns; //取得datatables每個欄位名稱           
            jQuery.each(fields, function(i, field) {
                //將datatables的值帶到表單對應的欄位
                tables.columns().every(function(index) {
                    //console.log(field.name + ',' + columns[index].name);
                    if (field.name == columns[index].name) {
                        $(":input[name='" + field.name + "']").val(data[index]);
                    }
                });
            });
            //$(":input[name='id']").attr("disabled", "disabled");
            $(":input[name='sex']").attr("disabled", "disabled");
            $(":input[name='dept_name']").attr("disabled", "disabled");
            $(":input[name='group_name']").attr("disabled", "disabled");
            //$(":input[name='signup_sn']").attr("disabled", "disabled");
            $('input:radio[name="lock_up"]').filter("[value='" + data[20] + "']").attr('checked', true);
            $(":input[name='oper']").val("2");
            $("#modal-form").modal("show"); //弹出框show

        });

        //詳細報名資料
        $('#example tbody').on('click', 'button#detail', function() {
            var data = tables.row($(this).parents('tr')).data();
            $.ajax({
                url: 'ajax/signupdata_ajax.php',
                data: {
                    oper: 8,
                    id: data[2],
                    signup_sn: data[18]
                },
                type: 'POST',
                dataType: 'json',
                success: function(JData) {
                    console.log('in');
                    //if (JData.content)
                    //toastr["error"](JData.error_message);
                    //    message(JData.error_message, "danger", 5000);
                    //else {
                        //tables.ajax.reload();
                        $("#modal-detail .modal-body").html(JData.content);
                        $("#modal-detail").modal("show"); //弹出框show
                    //}
                },
                error: function(xhr, ajaxOptions, thrownError) {}
            });


        });

        //存檔
        $("#btn-save").click(function() {
            var str = $("#add-form").serialize();
            if (!confirm("是否確定要存檔?")) return false;
            $.ajax({
                url: 'ajax/signupdata_ajax.php',
                data: $("#add-form").serialize(),
                type: 'POST',
                dataType: 'json',
                success: function(JData) {
                    console.log('in');
                    if (JData.error_code)
                    //toastr["error"](JData.error_message);
                        message(JData.error_message, "danger", 5000);
                    else {
                        toastr["success"]("資料修改成功!");
                        tables.ajax.reload();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {}
            });
        });
    }
);
