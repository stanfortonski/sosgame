(function(){
	$('#remind-form').validate({
		rules: {
			email: {
				required: true,
				email: true
			}
		},

		messages: {
			email: {
				required: 'Wypełnij to pole',
				email: 'Podany adres e-mail jest nie prawidłowy'
			}
		}
	});
})();
