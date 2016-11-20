$(
	function() {



		//init_table(0);
		$("body").tooltip({
            selector: "[title]"
        });
				init_table()
	}

);


function init_table()
{
	$.ajax({
		url: 'ajax/trip_ajax.php',
		data: { oper: 'dealing'},
		type: 'POST',
		dataType: "json",
			success: function(JData) {
					$('#trip_dealing').empty();

					if (JData.error_code)
							toastr["error"](JData.error_message);
					else{
					if (JData.COUNT==0){
							var row_part_new = "<center style='color:red'>您目前無任何記錄。</center><br>";
							$('#trip_dealing').append(row_part_new);

					}
					else{
						var row0="";
						row0=row0+"<thead><tr style=\"font-weight:bold\">";
						row0=row0+"<th>姓名</th>";
						row0=row0+"<th>單位</th>";
						row0=row0+"<th>職稱</th>";
						row0=row0+"<th>起始日期</th>";
						row0=row0+"<th>終止日期</th>";
						row0=row0+"<th>起始時間</th>";
						row0=row0+"<th>終止時間</th>";
						row0=row0+"<th>天數</th>";
						row0=row0+"<th>完成註記</th>";
						row0=row0+"</tr></thead>";

						for(var i=0;i<JData.EMPL_CHN_NAME.length;i++){
								var poname = JData.EMPL_CHN_NAME[i];
								var pocard = JData.POCARD[i];
								var povtype= JData.CODE_CHN_ITEM[i];
								var povdateB = JData.POVDATEB[i];
								var povdatee = JData.POVDATEE[i];
								var povhours =  JData.POVHOURS[i];
								var povdays   =  JData.POVDAYS[i];
								var povtimeb  =  JData.POVTIMEB[i];
								var povtimee  =  JData.POVTIMEE[i];
								var abroad     =  JData.ABROAD[i];
								var agentno   =  JData.AGENTNO[i];
								var serialno    =  JData.SERIALNO[i];
								var depart     =  JData.DEPART[i];
								var deptname=  JData.DEPT_SHORT_NAME[i];
								var tname     =  JData.CRJB_TITLE[i];

								row0=row0+"<tr><th>" + poname + "</th><th>" + deptname + "</th><th>" + tname + "</th><th>" + povdateB + "</th><th>"+povdatee + "</th><th>"+povtimeb + "</th><th>"
								+povtimee + "</th><th>"+povdays+"/"+povhours+"</th><th>"+"<a href=\"\">處理完成</a></th></tr>";
								//alert(poname);

						}
						$('#trip_dealing').append(row0);

					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
	});

	$.ajax({
		url: 'ajax/trip_ajax.php',
		data: { oper: 'canceled'},
		type: 'POST',
		dataType: "json",
			success: function(JData) {
					$('#trip_canceled').empty();

					if (JData.error_code)
							toastr["error"](JData.error_message);
					else{
					if (JData.COUNT==0){
							var row_part_new = "<center style='color:red'>您目前無任何記錄。</center><br>";
							$('#trip_canceled').append(row_part_new);

					}
					else{
						var row0="";
						row0=row0+"<thead><tr style=\"font-weight:bold\">";
						row0=row0+"<th>姓名</th>";
						row0=row0+"<th>單位</th>";
						row0=row0+"<th>職稱</th>";
						row0=row0+"<th>起始日期</th>";
						row0=row0+"<th>終止日期</th>";
						row0=row0+"<th>起始時間</th>";
						row0=row0+"<th>終止時間</th>";
						row0=row0+"<th>天數</th>";
						row0=row0+"<th>完成註記</th>";
						row0=row0+"</tr></thead>";

						for(var i=0;i<JData.EMPL_CHN_NAME.length;i++){
								var poname = JData.EMPL_CHN_NAME[i];
								var pocard = JData.POCARD[i];
								var povtype= JData.CODE_CHN_ITEM[i];
								var povdateB = JData.POVDATEB[i];
								var povdatee = JData.POVDATEE[i];
								var povhours =  JData.POVHOURS[i];
								var povdays   =  JData.POVDAYS[i];
								var povtimeb  =  JData.POVTIMEB[i];
								var povtimee  =  JData.POVTIMEE[i];
								var abroad     =  JData.ABROAD[i];
								var agentno   =  JData.AGENTNO[i];
								var serialno    =  JData.SERIALNO[i];
								var depart     =  JData.DEPART[i];
								var deptname=  JData.DEPT_SHORT_NAME[i];
								var tname     =  JData.CRJB_TITLE[i];

								row0=row0+"<tr><th>" + poname + "</th><th>" + deptname + "</th><th>" + tname + "</th><th>" + povdateB + "</th><th>"+povdatee + "</th><th>"+povtimeb + "</th><th>"
								+povtimee + "</th><th>"+povdays+"/"+povhours+"</th><th>"+"<a href=\"\">處理完成</a></th></tr>";
								//alert(poname);

						}
						$('#trip_canceled').append(row0);

					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {console.log(xhr.responseText);alert(xhr.responseText);}
	});

}
