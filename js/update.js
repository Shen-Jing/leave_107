$(
    function() {
		var oGrid =
        $("#jqGrid").jqGrid({
            url: 'ajax/update_ajax.php',
			editurl: 'ajax/update_ajax.php',
            pager: "#jqGridPager",
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
            ]
        });

        oGrid.jqGrid("navGrid", '#jqGridPager', {
			},
            // options for the Edit Dialog
            {
				afterSubmit: function(response, postdata) { 
					oGrid.jqGrid('setGridParam', {datatype:'json'}).trigger('reloadGrid'); //Reload after submit
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
							backdrop: true,
							size: 'small'
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
				
            });
    }
);