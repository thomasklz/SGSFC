$(document).ready(function(){
    $("#searchSocioBnt").click(function(e){
    e.preventDefault();
    toastr.options = {"progressBar": true, "positionClass": "toast-bottom-right", "showMethod":"slideDown","closeButton": true,};
     if($('#search').val().length == 0){
            toastr.error("No se permiten valores nulos","Error");
        }else{
            buscarSocio();
        }
    
    });
});

function buscarSocio(){
    $("#dataCheck").data('id_check',1);
    $("#cuotas").data('id_check',1);
    $("#dataCheckReunion").data('id_checkReunion',1);
    $("#searchSocioText").html("<p><img src='/img/ajax-loader.gif'> Buscando...</p>"); 
    $id=$("#search").val();
    $('#viewcoutas').attr("class","hide");
    $('#viewmultas').attr("class","hide");
    $('#viewreuniones').attr("class","hide");
    var url=$('#divRfSocioSearch').attr('data-path');
        $.ajax({
            data: {"cedula":$id},
            type: "POST",
            dataType: "json",
            url: url,
        })
        .done(function( data, textStatus, jqXHR ) {
                    var output = "";
                    $.each(data, function( key, value ) {
                        $("#searchSocioText").attr('data-idafiliado', + value['idafiliado']);
                        output += '<div class="col-lg-3 tile dark-blue" id="idAfiliado" data-idafiliado='+ value['idafiliado'] +'>';
                        output += '<h5> Código: ' +  value['idafiliado'] + '</h5>';
                        output += "</div>";
                        output += "<div class='col-lg-3 tile dark-blue'>";
                        output += '<h5> Nombre: ' +  value['nombre'] + '</h5>';
                        output += "</div>";
                        output += "<div class='col-lg-3 tile dark-blue'>";
                        output += '<h5> Apellido: ' +  value['apellido'] + '</h5>';
                        output += "</div>";
                        output += "<div class='col-lg-3 tile dark-blue'>";
                        output += '<h5> Cédula: ' +  value['cedula'] + '</h5>';
                        output += '</div>';      
                     });
                    $("#searchSocioText").html(output);
         })
         .fail(function( jqXHR, textStatus) {
                $("#searchSocioText").text("");
                toastr.error(jqXHR.responseJSON.message,"Error");  
        });
   
}