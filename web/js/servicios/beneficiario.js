$(document).ready(function(){
    toastr.options = {"progressBar": true, "positionClass": "toast-bottom-right", "showMethod":"slideDown","closeButton": true,};
    $('#searchSocio').click(function(e){
        e.preventDefault();
        var cedula=$("#search").val();
        if (cedula==""){
            toastr.error("No se permiten valores nulos", 'Error!');
            $("#search").focus();
        } 
        else {
            $("#errorBusqueda").html("<p><img src='/img/ajax-loader.gif'> Buscando...</p>"); 
            searchSocio();
        }
    }); 
    $('#bntSaveSocio').click(function(e){
        e.preventDefault();
        $idsocios=$("#idsocios").val();
        if ($idsocios==""){
            toastr.error("Primero debe buscar el socio", 'Error!');
            $("#search").focus();
        } 
        else
        {
            if (($("#nombres").val().length <= 0)||($("#apellidos").val().length <= 0)||($("#cedula").val().length <= 0)){
                    toastr.error("Existen valores nulos", 'Error!');
            }else{
                $nombre=$("#nombres").val();
                $apellido=$("#apellidos").val();
                $cedula= $("#cedula").val();
                $fechanacimiento= $("#datetimepicker").val();
                $sexo= $('input[name=sexo]:checked', '#registrationForm').val()
                $parentesco=$("#parentesco").val();
                $tipoafiliacion= $("#tipoafiliacion").val(); 
                $idafiliacionedependiente=$("#idsocios").val();
                var url=$('#idsocios').attr('data-path');
                $.ajax({
                    data: {"nombre":$nombre, "apellido":$apellido, "cedula":$cedula,
                            "fechanacimiento": $fechanacimiento, "sexo":$sexo, "parentesco":$parentesco,
                            "tipoafiliacion": $tipoafiliacion, "idafiliacionedependiente": $idafiliacionedependiente },
                    type: "POST",
                    dataType: "json",
                    url: url,
                })
                .done(function( data, textStatus, jqXHR ) {
                    $("#registrationForm")[0].reset(); 
                    toastr.success(data.message, 'Success!');
                    $("#search").focus();
                    searchSocio();
                 })
                 .fail(function( jqXHR, textStatus) {
                    toastr.error(jqXHR.responseJSON.message, 'Error');
                    $("#nombres").focus();
                });
            }
        }
    });  

});        
function searchSocio(){
    $id=$("#search").val();
    $("#errorBusqueda").show();
    var url=$('#response-container').attr('data-path');
        $.ajax({
            data: {"cedula":$id},
            type: "POST",
            dataType: "json",
            url: url,
        })
        .done(function( data, textStatus, jqXHR ){
               var output = "<h4>Socio encontrado</h4>";
                $.each(data, function( key, value ){
                        output += '<ul>';
                        output += '<li> Código: ' +  value['idafiliado'] + '</li>';
                        output += '<li> Nombre: ' +  value['nombre'] + '</li>';
                        output += '<li> Apellido: ' +  value['apellido'] + '</li>';
                        output += '<li> Cédula: ' +  value['cedula'] + '</li>';
                        output += '<li> # de Afiliados: ' +  value['Afiliados'] + '</li>';   
                        output += '</ul>';      
                        $("#idsocios").attr("value",value['idafiliado']);
                       });
                $("#response-container").attr("style","display:block");
                $("#response-container").attr("class","col-md-4 tile tile dark-blue");
                $("#response-container").html(output);
                $("#errorBusqueda").attr("style","display:none");
         })
         .fail(function( jqXHR, textStatus) {
             $("#errorBusqueda").attr("style","display:block");
             var output = "<div class='alert alert-danger alert-dismissable' id='errorBusqueda'>";
                 output += "<button type='button' class='close' data-dismiss='alert'>&times;</button>"
                 output +=  "La solicitud ha fallado: "+jqXHR.responseJSON.message;
                 output += "</div>";
                $("#errorBusqueda").html(output); 
             $("#response-container").attr("style","display:none");
             $("#idsocios").attr("value","");             
        });
}