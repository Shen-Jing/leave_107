$(document).ready(function() {
    $("#wrapper").toggleClass("toggled"); //預設展開
    $("#menu-toggle").click(function(e) {
        e.preventDefault();

        $("#wrapper").toggleClass("toggled");

        $('#wrapper.toggled').find("#sidebar-wrapper").find(".collapse").collapse('hide');

    });
});
