(function(){
	let minPasswordLength = 8,
		passwordLengthMessage = 'Hasło musi składać się z minimum '+minPasswordLength+' znaków',
		fillCellMessage = 'Wypełnij to pole';

	$('#change-pass-form').validate({
		rules: {
			'old-password': {
				required: true
			},

			password: {
				required: true,
				minlength: minPasswordLength
			},

			're-password': {
				required: true,
				minlength: minPasswordLength,
				equalTo: '#password'
			}
		},

		messages: {
			'old-password': {
				required: 'Wypełnij to polę podając swoje aktualne hasło'
			},

			password: {
				required: fillCellMessage,
				minlength: passwordLengthMessage
			},

			're-password': {
				required: fillCellMessage,
				minlength: passwordLengthMessage,
				equalTo: 'Podane hasło nie jest takie samo jak to powyżej'
			}
		}
	});
})();
