$(document).ready(function(){
    $('[data-user]').click(function(e){
        e.preventDefault();
        $("#modelafiliado").html("<p><img src='/img/ajax-loader.gif'> Buscando...</p>");
        $id=($(this).data('user'));
        var url = $('#modelafiliado').attr('data-path');
        $.ajax({
            data: {"id":$id},
            type: "GET",
            dataType: "json",
            url: url,
        })
        .done(function( data, textStatus, jqXHR ) {
            var  output = "<table  id='example-table' class='table table-striped table-bordered table-hover table-green'>";
            output +="<thead> <tr>  <th>Nombres</th>  <th>Cédula</th>  <th>Parentesco</th> <th>Fallecido</th> <th>Acción</th> </tr></thead>";
             output += "<tbody>";   
             var band =0; 
                $.each(data, function( key, value ) {
                    output += "<tr class='gradeC'>";
                    output += "<td>"+  value['nombre'] +" "+ value['apellido'] +"</td>";
                    output += "<td>"+  value['cedula'] + "</td>";
                    output += "<td>"+  value['parentesco'] + "</td>";
                    if (value['fallecido']==1){
                        output += "<td> Si </td>";
                        output += "<td>  </td>";
                    }else{
                        output += "<td> No </td>";
                        output += "<td><button id='cambio' data-change='"+  value['idafiliado'] + "' class='btn btn-xs btn-default r'>";
                        output += "<span class='fa fa-exchange'></span> Cambiar a Socio </button> </td>";
                        output += "</tr>";
                    }
                    
                    if(band==0){
                    $("#modelafiliado").html(output);
                    output="";
                    $("button.btn-default.r").off('click');
                    $("button.btn-default.r").on('click', function() {
                        $("#updateChange").html("<p><img src='/img/ajax-loader.gif'> Realizando cambio....</p>");   
                        var idafiliado=($(this).data('change'));    
                        redirect($id,idafiliado);
                     });
                    band=1;
                    }else{
                        $('#example-table tr:last').after(output);
                        output="";
                        $("button.btn-default.r").off('click');
                        $("button.btn-default.r").on('click', function() {
                        $("#updateChange").html("<p><img src='/img/ajax-loader.gif'> Realizando cambio....</p>");   
                        var idafiliado=($(this).data('change'));    
                        redirect($id,idafiliado);
                     });
                    }
                });
         })
         .fail(function(jqXHR, textStatus, errorThrown) {
            $("#modelafiliado").html(jqXHR.responseJSON.message);
        });
    });
});  
function redirect(idsocio,idafiliado){
  window.location.href = "cambiar/"+idsocio+"/"+idafiliado;
}