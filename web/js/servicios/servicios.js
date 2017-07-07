var arrayServicio = new Array();

$(document).ready(function() {
    $('#servicioTable').hide();
    $('[data-services]').click(function(e) {
        e.preventDefault();
        arrayServicio.length = 0;
        var id = ($(this).data('services'));
        var nombre = $(this).parents("tr").find("td").eq(0).html();
        var cedula = $(this).parents("tr").find("td").eq(1).html();
        TiempoAfiliacion(id, nombre, cedula);

    });

    $('#generateOrden').click(function(e) {
        toastr.options = { "progressBar": true, "positionClass": "toast-bottom-right", "showMethod": "slideDown", "closeButton": true, };
        var aux = $('input[name=codigo]').val();
        if (aux == "") {
            toastr.warning("Primero debe seleccionar el fallecido", "Advertencia");
        } else {
            if (($('#servicioTable').is(':visible') && $('input[name=idservicio]').is(':checked')) || ($('#servicioTable').is(':hidden'))) {
                if ($('input[name=idbono]').is(':checked')) {
                    GenerarOrden()
                } else {
                    toastr.warning("Debe seleccionar el bono", "Advertencia");
                }
            } else {
                toastr.warning("Debe seleccionar un servicio ", "Advertencia");
            }
        }
    });

    $('input[name=idservicio]').click(function(e) {
    	var bandera="";
    	bandera=$(this).parents("tr").find("td").eq(0).html();
        var result = jQuery.inArray(bandera, arrayServicio);
        if (result == -1){
        	arrayServicio.push(bandera);
        	$('#contentAfiliado').data('id_orden', arrayServicio);
        }else{
        arrayServicio.splice(result,1);
    	}
    });

});

function TiempoAfiliacion(id, nombre, cedula) {
    var url = $('#contentAfiliado').attr('data-path');
    debugger
    $.ajax({
            data: { "idafiliado": id },
            type: "GET",
            dataType: "json",
            url: url,
        })
        .done(function(data, textStatus, jqXHR) {
            $.each(data, function(key, value) {
                debugger
                if (value['length'] != 0) {
                    $('input[name=tiempo]').attr('value', +value['meses']);
                    if (value['meses'] >= 24) {
                        $('#servicioTable').show();
                    } else {
                        $('#servicioTable').hide();
                    }
                } else {
                    $('input[name=tiempo]').attr('value', 0);
                    $('#servicioTable').hide();
                }
                $('input[name=codigo]').attr('value', id);
                $('input[name=nombapel]').attr('value', nombre);
                $('input[name=cedula]').attr('value', cedula);
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            debugger
            toastr.error(jqXHR.responseJSON.message);
        });
}

function GenerarOrden() {
    var idusuario = $("#hdnSession").attr("data-value");
    var idafiliado = $('input[name=codigo]').val();
    var idbono = $('input[name=idbono]').val();
    var url = $('#contentOrden').attr("data-path");
    $("#listOrden").html("<p class='text-red'><img src='/img/ajax-loader.gif'> Generando orden...</p>");
    $.ajax({
            data: { "idafiliado": idafiliado, "idbono": idbono, "idusuario": idusuario },
            type: "POST",
            dataType: "json",
            url: url,
        })
        .done(function(data, textStatus, jqXHR) {
            $.each(data, function(key, value) {
                if ($('#servicioTable').is(':visible')) {
                    GenerarServicos(value['idorden']);
                } else {
                	$('#listOrden').html("<p class='text-success'>Orden generada correctamente<p>");	
                    window.location.href = "orden/registrar";
                }
            });

        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $('#listOrden').html(jqXHR.responseJSON.message);
        });
}

function GenerarServicos(idOrden) {
    var idservicio = arrayServicio;
    var url = $('#contentAfiliado').attr('data-servicios');
    $.ajax({
            data: { "idorden": idOrden, "idservicio": JSON.stringify(idservicio) },
            type: "POST",
            dataType: "json",
            url: url,
        })
        .done(function(data, textStatus, jqXHR) {
            $.each(data, function(key, value) {
                $('#listOrden').html("<p class='text-success'>Orden generada correctamente<p>");
                 window.location.href = "orden/registrar";
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $('#listOrden').html(jqXHR.responseJSON.message);
        });
}
