$(document).ready(function() {
    $("#searchSocioBnt").click(function(e) {
        e.preventDefault();
        toastr.options = { "progressBar": true, "positionClass": "toast-bottom-right", "showMethod": "slideDown", "closeButton": true, };
        if ($('#search').val().length == 0) {
            toastr.error("No se permiten valores nulos", "Error");
        } else {
            buscarSocio();
        }

    });
});

function buscarSocio() {

    $("#searchSocioText").html("<p><img src='/img/ajax-loader.gif'> Buscando...</p>");
    $id = $("#search").val();
    var url = $('#divRfSocioSearch').attr('data-path');
    $.ajax({
            data: { "cedula": $id },
            type: "POST",
            dataType: "json",
            url: url,
        })
        .done(function(data, textStatus, jqXHR) {
            var output = "";
            $.each(data, function(key, value) {
                output += "<div class='col-md-3 tile dark-blue'>";
                output += 'Nombre: ' + value['nombre'] + '';
                output += "</div>";
                output += "<div class='col-md-3 tile dark-blue'>";
                output += 'Apellido: ' + value['apellido'] + '';
                output += "</div>";
                output += "<div class='col-md-2 tile dark-blue'>";
                output += 'C.I.: ' + value['cedula'] + '';
                output += "</div>";
                output += "<div class='col-md-2 tile dark-blue'>";
                output += 'Tipo: ' + value['tipoafiliacion'] + '';
                output += "</div>";
                output += "<div class='col-md-2 tile dark-blue text-center'>";
                output += '<button class="btn btn-xs btn-red">';
                output += '<span class="fa fa-plus-circle"></span>Fallecido</button>';
                output += '</div>';

                $("#searchSocioText").data("id_afiliado", value['idafiliado']);
                $("#searchSocioText").html(output);
                $("button.btn-red").on('click', function() {
                    bootbox.confirm({
                        title: 'Confirmation',
                        message: "Si el fallecido es un SOCIO recuerde cambiar el tipo de afiliación en Socios>> Listar Socios>> Ver afiliados . ¿Desea agregarlo a lista de fallecidos?",
                        callback: function(result) {
                            if (result) {
                                var idafiliado = $("#searchSocioText").data("id_afiliado");
                                window.location.href = "estado/fallecidos/" + idafiliado + '?estado=' + 1;
                            }
                        }
                    });

                });
            });

        })
        .fail(function(jqXHR, textStatus) {
            $("#searchSocioText").text("");
            toastr.error(jqXHR.responseJSON.message, "Error");
        });

}
