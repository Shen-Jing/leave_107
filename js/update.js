$.jgrid.defaults.responsive = true;
$.jgrid.defaults.styleUI = 'Bootstrap';
$.jgrid.useJSON = true;

$(
    function() {
        $("#jqGrid").jqGrid({
            url: 'ajax/update_ajax.php',
			editurl: 'ajax/update_ajax.php',
            // we set the changes to be made at client side using predefined word clientArray
			mtype: 'POST',
            datatype: "json",
            colModel: [{
                    label: 'ID',
                    name: 'SERIALNO',
                    editable: true,
					hidden: true,
					editoptions:{readonly:'readonly'}
                },
                {
                    label: '姓名',
                    name: 'EMPL_CHN_NAME',
                    editable: true,
                    editoptions:{readonly:'readonly'}
                },
                {
                    label: '假別',
                    name: 'CODE_CHN_ITEM',
                    editable: true // must set editable to true if you want to make the field editable
                },
                {
                    label: '起始日期',
                    name: 'POVDATEB',
                    editable: true
                },
                {
                    label: '終止日期',
                    name: 'POVDATEE',
                    editable: true
                },
                {
                    label: '起始時間',
                    name: 'POVTIMEB',
                    editable: true
                },
                {
                    label: '終止時間',
                    name: 'POVTIMEE',
                    editable: true
                },
                {
                    label: '總時數',
                    name: 'POVHOURS',
                    editable: true
                },
                {
                    label: '職務代理人',
                    name: 'AGENTNO',
                    editable: true
                }
            ],
            viewrecords: true,
            rowNum: 10,
            pager: "#jqGridPager",
            autowidth: true,
            height: 'auto'
        });

        $('#jqGrid').jqGrid("navGrid", '#jqGridPager', {
			edit: true,
			del: true,
			add: false,
			search: true,
			refresh: true, 
			view: true,
			},
            // options for the Edit Dialog
            {
				resize: false,
				checkOnSubmit: true,
				recreateForm: true,
				closeAfterEdit: true,
				afterSubmit: function(response, postdata) { 
					if(response.responseText == null){ 
						bootbox.alert({
							message: '錯誤',
							backdrop: true
						}); 
						return [false,"error!"]; 
					}
					else{ 
						bootbox.alert({
							message: '成功',
							backdrop: true
						}); 
						return [true,"OK"]; 
					}
				}
            },
            // options for the Add Dialog
            {
                //template: modal
            },
            // options for the Delete Dailog
            {
                resize: false,
				checkOnSubmit: true
            });
    }
);