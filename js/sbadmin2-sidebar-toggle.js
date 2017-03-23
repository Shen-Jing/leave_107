
// 17/03/13 使用cookie記錄sidebar收起/摺疊狀態
$(document).ready(function() {
	
	// default, collapse or expand
	// c : collapsed
	if(getCookie("sbar") === "c")
		toggleSidebar();
	
	//設定每當按下menu toggle更新cookie的值
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
		
        toggleSidebar();
		if( $("#wrapper").hasClass("toggled") )
			setCookie("sbar", "c");
		else
			setCookie("sbar", "e");
    });
});


function toggleSidebar(){
	$("#wrapper").toggleClass("toggled");

	$('#wrapper.toggled').find("#sidebar-wrapper").find(".collapse").collapse('hide');
}


function setCookie(cname, cvalue)
{
    var d = new Date();
    d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname)
{
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}