$.validator.setDefaults({
		errorElement: 'div',
		errorClass: 'error arrowed col-sm-2 mx-4 mx-sm-0 invalid-feedback bg-danger',

    highlight: function(element) {
				if (!$(element).closest('form').hasClass('novalidation'))
        	$(element).removeClass('is-valid').addClass('is-invalid');
    },

    unhighlight: function(element) {
			if (!$(element).closest('form').hasClass('novalidation'))
        $(element).removeClass('is-invalid').addClass('is-valid');
    },

		errorPlacement: function(error, element){
			if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
        error.insertAfter(element.parent().parent());
      }
      else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
        error.appendTo(element.parent().parent());
      }
			else error.insertAfter(element.parent());
		}
});

$.validator.addMethod("notEqual", function(value, element, params) {
  return this.optional(element) || value != params;
}, "Please specify a different (non-default) value");

$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'File size must be less than {0}');
