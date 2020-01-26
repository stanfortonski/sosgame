(function(){
	let minPasswordLength = 8,
		passwordLengthMessage = 'Hasło musi składać się z minimum '+minPasswordLength+' znaków',
		fillCellMessage = 'Wypełnij to pole';

	$('#register-form').validate({
		rules: {
			login: {
				required: true,
				minlength: 3,
				pattern: /^[A-Za-z0-9]+(?:[_-][A-Za-z0-9]+)*$/
			},

			email: {
				required: true,
				email: true,
			},

			password: {
				required: true,
				minlength: minPasswordLength
			},

			're-password': {
				required: true,
				minlength: minPasswordLength,
				equalTo: '#password'
			},

			agreement: 'required'
		},

		messages: {
			login: {
				required: fillCellMessage,
				minlength: 'Login musi składać się minimum z 3 znaków',
				pattern: 'Login zawiera niedozolone znaki'
			},

			email: {
				required: fillCellMessage,
				email: 'Podany adres e-mail jest nie prawidłowy'
			},

			password: {
				required: fillCellMessage,
				minlength: passwordLengthMessage
			},

			're-password': {
				required: fillCellMessage,
				minlength: passwordLengthMessage,
				equalTo: 'Podane hasło nie jest takie samo jak to powyżej'
			},

			agreement: 'Zaakceptuj regulamin'
		}
	});
})();
