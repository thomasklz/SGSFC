$(document).ready(function(){
    $("#dataCheck").data('id_check',1);
	toastr.options = {"progressBar": true, "positionClass": "toast-bottom-right", "showMethod":"slideDown","closeButton": true,};
    $('#multas').click(function(e){
    	e.preventDefault();
        $('#viewmultas').show();
        if($('#searchSocioText').is(':empty')){
            toastr.warning("Primero debe buscar el socio","Advertencia");
            $('#viewmultas').hide();
        }else{
            var check=$("#dataCheck").data('id_check');
            if(check==1){
                cargarMultas();
            }
        }
    });

    function removeFilas(){
        $('#pagoMulta tbody tr').slice(1).remove();
    } 

    function cargarMultas(){
         removeFilas();
        var idAfiliado =$('#searchSocioText').attr('data-idafiliado');
        var url = $('#ctnTable').attr('data-path');
        $("#loading").html("<p><img src='/img/ajax-loader.gif'> Actualizando...</p>");
        $.ajax({
            data: {"idafiliado":idAfiliado},
            type: "GET",
            dataType: "json",
            url: url,
        })
        .done(function( data, textStatus, jqXHR ) {
             $.each(data, function( key, value ) {
                    var output ="";
                    output += "<tr class='gradeC multa"+ value['idmulta'] +"'>";
                    output += "<td>"+  value['temas'] + "</td>";
                    output += "<td class='text-center'>"+  value['fechareunion'] + "</td>";
                    output += "<td class='text-center'> $ "+  value['valormulta'] + "</td>";
                    output += '<td class="text-center"><button class="btn btn-xs btn-red" data-val="'+value['valormulta']+'" data-idvalue="'+value['idmulta']+'">';
                    output += "<span class='fa fa-money'></span> pagar</button></td>";
                    output += "</tr>";
                    $('#pagoMulta tr:last').after(output);
                    $("button.btn-red").off('click');
                    $("button.btn-red").on('click', function() {
                        var idmulta = $(this).data('idvalue'); 
                        var valorMulta =  $(this).data('val');
                        pagoMulta(idmulta,valorMulta); 
                    });
             });
            $("#loading").html("");
            $("#dataCheck").data('id_check',0);
         })
         .fail(function(jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR.responseJSON.message);
                $("#loading").html("");
            });
    }

    function pagoMulta(idMulta, valor){
        toastr.options = {"progressBar": true, "positionClass": "toast-bottom-right", "showMethod":"slideDown","closeButton": true,};
        bootbox.confirm({
                title: 'Confirmation',
                message: "Desea realizar el pago ?", 
                callback: function(result){ 
                    if(result) 
                    {
                        var url = $("#cntAllMulta").attr("data-path");
                        var id= $('#idAfiliado').attr('data-idafiliado');
                        var idusuario = $("#hdnSession").attr("data-value");
                        $.ajax({
                                data: {"valor":valor,"idusuario":idusuario,"idafiliado":id,"idmulta": idMulta},
                                url: url,
                                dataType: "json",
                                type: "POST",
                            })
                        .done(function( data, textStatus, jqXHR ) {
                                toastr.success(data.message,"PAGO");
                                $("tr.multa"+idMulta).remove();
                            })
                        .fail(function( jqXHR, textStatus) {
                                toastr.error(data.message,"PAGO");
                            });
                    }
                }
            });
            return false;
    } 

});   