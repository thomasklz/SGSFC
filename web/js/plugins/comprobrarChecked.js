$(document).ready(function(){
	$("#AñadirMes").click(function(e) { 
		e.preventDefault();
		comprobracionChecked();
	});
    $('#PagarMes').click(function(e){
        var totalPago = $("#demo").attr("data-total");
        if ((totalPago==0) || (totalPago=="")){
            toastr.options = {"progressBar": true, "positionClass": "toast-bottom-right", "showMethod":"slideDown","closeButton": true,};
            toastr.error("No existe mes a pagar","PAGO");
        }else{
        var url = $("#PagarMes").attr("data-path");
        bootbox.confirm({
                title: 'Confirmation',
                message: "Desea realizar el pago ?", 
                callback: function(result){ 
                    if(result) 
                    {
                        $('#carga').html("<p class='text-red'><img src='/img/ajax-loader.gif'> Realizando el pago...</p>");
                        var id= $('#idAfiliado').attr('data-idafiliado');
                        var idusuario = $("#hdnSession").attr("data-value");
                        var arrayMes = $("#demo").attr("data-array");
                        var valor = $("#hdnValorMes").val();
                        $.ajax({
                                data: {"valor":valor,"idusuario":idusuario,"idafiliado":id,"idmes": arrayMes},
                                url: url,
                                dataType: "json",
                                type: "POST",
                            })
                        .done(function( data, textStatus, jqXHR ) {
                                toastr.options = {"progressBar": true, "positionClass": "toast-bottom-right", "showMethod":"slideDown","closeButton": true,};
                                toastr.success(data.message,"PAGO");
                                eliminarFilas();
                                $('#cuotas').click();
                                $('#cuotas').click();
                                $('#demo').attr("data-total","0");
                                $('#carga').empty()
                            })
                        .fail(function( jqXHR, textStatus) {
                                toastr.options = {"progressBar": true, "positionClass": "toast-bottom-right", "showMethod":"slideDown","closeButton": true,};
                                toastr.error(data.message,"PAGO");
                            });
                    }
                }
            });
            e.stopPropagation(); 
            return false;
        }
    });
});
function eliminarFilas(){
    var trs=$("#pagoMes tr").length;
    while(trs>=3)
        {
            $("#pagoMes tr:last").remove();
            trs=trs-1;
        }
}
function comprobracionChecked(){
        var arrayIdMes = [];
		var $total=0;
        eliminarFilas();
        if($("#Enero").is(':checked')) {  
            $('#pagoMes tr:last').after('<tr><td>Enero</td><td>'+$('#Enero').val()+'</td></tr>');
            $total= $total + parseFloat($('#Enero').val()); 
            arrayIdMes.push($("#Enero" ).data("idmes"));
        } 
        if($("#Febrero").is(':checked')) {  
            $('#pagoMes tr:last').after('<tr><td>Febrero</td><td>'+$('#Febrero').val()+'</td></tr>');
            $total= $total + parseFloat($('#Febrero').val());
            arrayIdMes.push($("#Febrero" ).data("idmes"));
        } 
        if($("#Marzo").is(':checked')) {  
            $('#pagoMes tr:last').after('<tr><td>Marzo</td><td>'+$('#Marzo').val()+'</td></tr>');
            $total= $total + parseFloat($('#Marzo').val());
            arrayIdMes.push($("#Marzo" ).data("idmes"));
        } 
        if($("#Abril").is(':checked')) {  
            $('#pagoMes tr:last').after('<tr><td>Abril</td><td>'+$('#Abril').val()+'</td></tr>');
            $total= $total + parseFloat($('#Abril').val());
            arrayIdMes.push($("#Abril" ).data("idmes"));
        } 
        if($("#Mayo").is(':checked')) {  
             $('#pagoMes tr:last').after('<tr><td>Mayo</td><td>'+$('#Mayo').val()+'</td></tr>');
             $total= $total + parseFloat($('#Mayo').val());
             arrayIdMes.push($("#Mayo" ).data("idmes"));
        } 
         if($("#Junio").is(':checked')) {  
             $('#pagoMes tr:last').after('<tr><td>Junio</td><td>'+$('#Junio').val()+'</td></tr>');
             $total= $total + parseFloat($('#Junio').val());
             arrayIdMes.push($("#Junio" ).data("idmes"));
        } 
         if($("#Julio").is(':checked')) {  
             $('#pagoMes tr:last').after('<tr><td>Julio</td><td>'+$('#Julio').val()+'</td></tr>');
             $total= $total + parseFloat($('#Julio').val());
             arrayIdMes.push($("#Julio" ).data("idmes"));
        } 
         if($("#Agosto").is(':checked')) {  
             $('#pagoMes tr:last').after('<tr><td>Agosto</td><td>'+$('#Agosto').val()+'</td></tr>');
             $total= $total + parseFloat($('#Agosto').val());
             arrayIdMes.push($("#Agosto" ).data("idmes"));
        } 
         if($("#Septiembre").is(':checked')) {  
             $('#pagoMes tr:last').after('<tr><td>Septiembre</td><td>'+$('#Septiembre').val()+'</td></tr>');
             $total= $total + parseFloat($('#Septiembre').val());
             arrayIdMes.push($("#Septiembre" ).data("idmes"));
        } 
         if($("#Octubre").is(':checked')) {  
             $('#pagoMes tr:last').after('<tr><td>Octubre</td><td>'+$('#Octubre').val()+'</td></tr>');
             $total= $total + parseFloat($('#Octubre').val());
             arrayIdMes.push($("#Octubre" ).data("idmes"));
        } 
         if($("#Noviembre").is(':checked')) {  
             $('#pagoMes tr:last').after('<tr><td>Noviembre</td><td>'+$('#Noviembre').val()+'</td></tr>');
             $total= $total + parseFloat($('#Noviembre').val());
             arrayIdMes.push($("#Noviembre" ).data("idmes"));
        } 
         if($("#Diciembre").is(':checked')) {  
             $('#pagoMes tr:last').after('<tr><td>Diciembre</td><td>'+$('#Diciembre').val()+'</td></tr>');
             $total= $total + parseFloat($('#Diciembre').val());
             arrayIdMes.push($("#Diciembre" ).data("idmes"));
        } 
 		$('#pagoMes tr:last').after('<tr><td class="text-right"><h5><strong><em>Total a pagar</em></strong></h5></td><td class="text-red"><h5><strong> $'+ $total +'<strong></h5></td></tr>');	
        var jsonString = JSON.stringify(arrayIdMes);
        $("#demo").attr("data-array", jsonString);
        $("#demo").attr("data-total", $total);
}      