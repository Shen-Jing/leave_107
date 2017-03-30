$(document).ready(function() {
    $("#menu-toggle").click(function(e) {
        e.preventDefault();

        $("#wrapper").toggleClass("toggled");

        $('#wrapper.toggled').find("#sidebar-wrapper").find(".collapse").collapse('hide');

    });
});
