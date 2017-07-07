$(document).ready(function(){
    $("#dataCheckReunion").data('id_checkReunion',1);
	toastr.options = {"progressBar": true, "positionClass": "toast-bottom-right", "showMethod":"slideDown","closeButton": true,};
    $('#reuniones').click(function(e){
    	e.preventDefault();
        $('#viewreuniones').show();
        if($('#searchSocioText').is(':empty')){
            toastr.warning("Primero debe buscar el socio","Advertencia");
            $('#viewreuniones').hide();
        }else{
            var check=$("#dataCheckReunion").data('id_checkReunion');
            if(check==1){
                cargarReuniones();
            }
        }
    });

    function removeFilas(){
        $('#pagoReunion tbody tr').slice(1).remove();
    } 

    function cargarReuniones(){
        removeFilas();
        var idAfiliado =$('#searchSocioText').attr('data-idafiliado');
        var url = $('#ctnTableReunion').attr('data-path');
        $("#loadingReunion").html("<p><img src='/img/ajax-loader.gif'> Actualizando...</p>");
        $.ajax({
            data: {"idafiliado":idAfiliado},
            type: "GET",
            dataType: "json",
            url: url,
        })
        .done(function( data, textStatus, jqXHR ) {
             $.each(data, function( key, value ) {
                    var output ="";
                    output += "<tr class='gradeC reunion"+ value['idreunion'] +"'>";
                    output += "<td>"+  value['temas'] + "</td>";
                    output += "<td class='text-center'>"+  value['fechareunion'] + "</td>";
                    output += "<td class='text-center'> $ "+  value['valorreunion'] + "</td>";
                    output += '<td class="text-center"><button class="btn btn-xs btn-red r" data-val="'+value['valorreunion']+'" data-idvalue="'+value['idreunion']+'">';
                    output += "<span class='fa fa-money'></span> pagar</button></td>";
                    output += "</tr>";
                    $('#pagoReunion tr:last').after(output);
                    $("button.btn-red.r").off('click');
                    $("button.btn-red.r").on('click', function() {
                        var idreunion = $(this).data('idvalue'); 
                        var valorReunion =  $(this).data('val');
                        pagoReunion(idreunion,valorReunion); 
                    });
             });
            $("#loadingReunion").html("");
            $("#dataCheckReunion").data('id_checkReunion',0);
         })
         .fail(function(jqXHR, textStatus, errorThrown) {
                toastr.error(jqXHR.responseJSON.message);
                $("#loadingReunion").html("");
            });
    }

    function pagoReunion(idreunion, valorReunion){
        bootbox.confirm({
                title: 'Confirmation',
                message: "Desea realizar el pago ?", 
                callback: function(result){ 
                    if(result) 
                    {
                        var url = $("#cntAllReunion").attr("data-path");
                        var id= $('#idAfiliado').attr('data-idafiliado');
                        var idusuario = $("#hdnSession").attr("data-value");
                        $.ajax({
                                data: {"valor":valorReunion,"idusuario":idusuario,"idafiliado":id,"idreunion": idreunion},
                                url: url,
                                dataType: "json",
                                type: "POST",
                            })
                        .done(function( data, textStatus, jqXHR ) {
                                toastr.success(data.message,"Reunión");
                                $("tr.reunion"+idreunion).remove();
                            })
                        .fail(function( jqXHR, textStatus) {
                                toastr.error(data.message,"Reunión");
                            });
                    }
                }
            });
            return false;
    } 

});   