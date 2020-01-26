(function(){
	let badMailMessage = 'Podany adres e-mail jest nie prawidłowy',
		fillCellMessage = 'Wypełnij to pole';

	$('#change-mail-form').validate({
		rules: {
			email: {
				required: true,
				email: true,
			},

			're-email': {
				required: true,
				email: true,
				equalTo: '#email'
			},
		},

		messages: {
			email: {
				required: fillCellMessage,
				email: badMailMessage
			},

			're-email': {
				required: fillCellMessage,
				email: badMailMessage,
				equalTo: 'Podany adres e-mail nie zgadza się z tym podanym powyżej'
			},
		}
	});
})();
