$(document).ready(function() {
    $("input[name=password2]").change(function() {
        comprobrarCheck();
    }).change();
    $("input[name=password]").change(function() {
        comprobrarCheck();
    }).change();
});

function comprobrarCheck() {
    var pas1 = $('input[name=password]').val();
    var pas2 = $('input[name=password2]').val();
    if (pas2 != "") {
        if (pas1 != pas2) {
            $("#check").html('Password no coinciden');
            $('input[name=password]').css("border", "1px solid red");
            $("#send").attr("disabled", true);
            $('input[name=password2]').css("border", "1px solid red");
        } else {

            $("#send").attr("disabled", false);
            $("#check").html('');
            $('input[name=password]').css("border", "1px solid");
            $('input[name=password2]').css("border", "1px solid");
        }
    }

}
