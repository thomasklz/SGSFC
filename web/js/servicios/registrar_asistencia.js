$(document).ready(function() {
    toastr.options = { "progressBar": true, "positionClass": "toast-bottom-right", "showMethod": "slideDown", "closeButton": true, };
    $('[data-afiliado]').click(function(e) {
        e.preventDefault();
        var idAfiliado = ($(this).data('afiliado'));
        var url = $('#contentTable').attr('data-path');
        var idreunion = $('#idreunion').val();
        $.ajax({
                data: { "idafiliado": idAfiliado, "idreunion": idreunion },
                type: "POST",
                dataType: "json",
                url: url,
            })
            .done(function(data, textStatus, jqXHR) {
                $('#' + idAfiliado).remove();
                var output = "<span class='badge red'>Inasistencia</span>";
                $('#falta' + idAfiliado).html(output);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR.responseJSON.message);
            });

    });
    $('[data-id]').click(function(e) {
        e.preventDefault();
        var url = $('#beneficiario').attr('data-path');
        var idAfiliado = ($(this).data('id'));
        $("#modelafiliado").html("<p><img src='/img/ajax-loader.gif'> Buscando...</p>");
        $.ajax({
                data: { "idafiliado": idAfiliado },
                type: "GET",
                dataType: "json",
                url: url,
            })
            .done(function(data, textStatus, jqXHR) {
                var output = "<table id='example-table' class='table table-striped table-bordered table-hover table-green'>";
                output += "<thead> <tr><th>Tema</th> <th>Valor reunión</th><th>Fecha reunión</th><th>Acción</th></tr></thead>";
                output += "<tbody>";
                $.each(data, function(key, value) {
                    output += "<tr class='gradeC'>";
                    output += "<td>" + value['temas'] + "</td>";
                    output += "<td>" + value['valormulta'] + "</td>";
                    output += "<td>" + value['fechareunion'] + "</td>";
                    output += "<td> <form action='multas/" + value['idmulta'] + "' method='delete'> <button class='btn btn-xs btn-red' type='submit'>";
                    output += "<span class='fa fa-times'></span> Eliminar</button></form></td>";
                    output += "</tr>";
                });
                output += "</tbody>";
                output += "</table>";
                $("#modelafiliado").html(output);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                $("#modelafiliado").html(jqXHR.responseJSON.message);
            });
    });
});
