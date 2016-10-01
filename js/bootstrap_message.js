var message_id = 1;

function message(content, type, time) {
    message_id = message_id + 1;
    var row_message = "<div class='alert alert-" + type + "' id='message_" + message_id + "'>";
    row_message = row_message + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
    if (type == "success") row_message = row_message + "<i class='fa fa-check-circle'></i>";
    if (type == "info") row_message = row_message + "<i class='fa fa-info-circle'></i>";
    if (type == "warning") row_message = row_message + "<i class='fa fa-exclamation-circle'></i>";
    if (type == "danger") row_message = row_message + "<i class='fa fa-times-circle'></i>";
    row_message = row_message + " <span>" + content + "</span>";
    row_message = row_message + "</div>";
    $("#message").append(row_message);
    //$("#message").show();
    if (time > 0) setTimeout(function() { $("#message_" + message_id).remove() }, time);
}
