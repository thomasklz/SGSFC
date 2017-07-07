$(document).ready(function() {
    toastr.options = { "progressBar": true, "positionClass": "toast-bottom-right", "showMethod": "slideDown", "closeButton": true, };
    $('[data-orden]').click(function(e) {
    e.preventDefault();
    var url = $('#flexModalLabel').attr('data-path');
    var idorden = ($(this).data('orden'));
    $("#modelafiliado").html("<p><img src='/img/ajax-loader.gif'> Buscando orden...</p>");
    $.ajax({
            data: { "idorden": idorden },
            type: "GET",
            dataType: "json",
            url: url,
        })
        .done(function(data, textStatus, jqXHR) {
            var out="";
            var aux=0;
            $.each(data, function(key, value) {
                 if(aux==0){
                out +="<h5 class='text-success strong'>ORDEN REALIZADA</h5><div class='row'>";
                out +="<div class='col-md-8'><strong>Fecha egreso:</strong> " +value['fechaegreso']+ "</div>";
                out +="<div class='col-md-4'><strong>Orden #:</strong> " +value['idorden']+ " </div>";
                out +="<div class='col-md-12'><strong>Total orden:</strong> ";
                out +="<span class='text-success strong'>$" +value['ValorEgreso']+ "</span></div>"
                out +="<div class='col-md-6'><strong>Bono:</strong> " +value['bono']+ "</div>";
                out +="<div class='col-md-6'><strong>Valor:</strong> $" +value['ValorBono']+ "</div>";
                out +="<div class='col-md-12'><strong>Servicios</strong></div>";
                aux=1;
                }
                if (typeof(value['servicio']) === "undefined"){
                    out +="<div class='col-md-12 text-red'>No existen Servicios para esta orden</div>";
                }else{
                    out +="<div class='col-md-6'><strong>"+aux+"</strong> "+value['servicio']+" </div>";
                    out +="<div class='col-md-6'><strong>Valor:</strong> $" +value['ValorServicio']+ " </div>";
                    aux=aux+1;
                }
            });
            out+="</div>";
            $("#modelafiliado").html(out);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            toastr.error(jqXHR.responseJSON.message);
    });
    });
});

