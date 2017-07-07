$(document).ready(function() {
    $('#datetimepicker')
        .datepicker({
            format: 'yyyy/mm/dd'
        })
        .on('changeDate', function(e) {
            // Revalidate the date field
            $('#registrationForm').bootstrapValidator('revalidateField', 'datetimepicker');
        });


$('#registrationForm').bootstrapValidator({
 
	 feedbackIcons: {
		 valid: 'fa fa-check',
		 invalid: 'fa fa-warning',
		 validating: 'fa fa-refresh'
	 },
 
	 fields: {
 
		 nombres: {
			 validators: {
				 notEmpty: {
					 message: 'Los nombres son requeridos'
				 },
				  regexp: {
					 regexp: /^[a-zA-Z ]+$/,
					 message: 'Los nombres solo puede contener letras'
 
				 }
				
			 }
		 },
 
		 apellidos: {
			 validators: {
				 notEmpty: {
					 message: 'Los apellidos son requeridos'
				 },
				 regexp: {
					 regexp: /^[a-zA-Z ]+$/,
					 message: 'Los apellidos solo puede contener letras'
 
				 }
			 }
		 },
 
		 datetimepicker: {
			 validators: {
				 notEmpty: {
					 message: 'La fecha de nacimiento es requerida y no puede ser vacía'
				 }
			 }
		 },		 
 
		 cedula: {
			 validators: {
				 notEmpty: {
					 message: 'La cédula es requerida y no puede estar vacía'
				 },
				 stringLength: {
					 min: 10,
					 message: 'La cédula debe contener al menos 10 caracteres'
				 },
				 regexp: {
					 regexp: /^[0-9]+$/,
					 message: 'La cédula solo puede contener números'
 
				 }
 
			 }
 
		 },

	 }
 
});



 

});
 