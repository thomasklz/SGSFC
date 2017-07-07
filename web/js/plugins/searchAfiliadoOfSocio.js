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
            output +="<thead> <tr>  <th>Nombres</th> <th>Apellidos</th>  <th>Cédula</th>  <th>Parentesco</th> </tr> </thead>";
                $.each(data, function( key, value ) {
                    output += "<tbody>"; 
                    output += "<tr class='gradeC'>";
                    output += "<td>"+  value['nombre'] + "</td>";
                    output += "<td>"+  value['apellido'] + "</td>";
                    output += "<td>"+  value['cedula'] + "</td>";
                    output += "<td>"+  value['parentesco'] + "</td>";
                    output += "</tr>";
                    output += "</tbody>";
                });
                output +="</table>";  
                $("#modelafiliado").html(output);
         })
         .fail(function(jqXHR, textStatus, errorThrown) {
            $("#modelafiliado").html(jqXHR.responseJSON.message);
        });
    });
});  