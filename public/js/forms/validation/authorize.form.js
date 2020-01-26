(function(){
	$('#login-form').validate({
		rules: {
			login: 'required',
			password: {
				required: true,
				minlength: 8
			}
		},

		messages: {
			login: 'Wypełnij to pole',
			password: 'Wypełnij to pole'
		}
	});
})();
