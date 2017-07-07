$(document).ready(function(){
	$("#cuotas").data('id_check',1);
    $("#cuotas").click(function(e){
		e.preventDefault();
        $('#viewcoutas').show();
        if($('#searchSocioText').is(':empty')){
            toastr.options = {"progressBar": true, "positionClass": "toast-bottom-right", "showMethod":"slideDown","closeButton": true,};
            toastr.warning("Primero debe buscar el socio","Advertencia");
            $('#viewcoutas').hide();
        }else{
		    var check=$("#cuotas").data('id_check');
            if(check==1){
            MesesAPagar();
            }
        }
	});
});
function MesesAPagar(){
    $("#checklists").html("<p><img src='/img/ajax-loader.gif'> Actualizando...</p>"); 
    var url= $('#checklists').attr('data-path');
    var year= $('#year').val();
    var id= $('#idAfiliado').attr('data-idafiliado');
    	$.ajax({
            data: {"year":year,"idafiliado":id},
            type: "GET",
            dataType: "json",
            url: url,
        })
        .done(function( data, textStatus, jqXHR ) {
                    var output = "";
                    $.each(data, function( key, value ) {
                        output += "<label class='cuota"+value['idmes']+"'>";
                        output += '<input type="hidden" id="hdnValorMes" value='+value['valor']+'/>'
                        output += '<input data-idmes='+value['idmes']+' value='+value['valor']+' id='+value['mes']+' type="checkbox"/>' + value['mes'] ;
                        output += "</label>";
                     });
                    $("#checklists").html(output);
                    $("#cuotas").data('id_check',0);
         })
         .fail(function( jqXHR, textStatus) {
             var output = "<div class='alert alert-danger alert-dismissable'>";
                 output += "<button type='button' class='close' data-dismiss='alert'>&times;</button>"
                 output +=  jqXHR.responseJSON.message;
                 output += "</div>";
                $("#checklists").html(output); 
        });
}