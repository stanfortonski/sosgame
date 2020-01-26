(function(){
	let nameMinLength = 3,
		nameMaxLength = 40,
		fillCellMessage = 'Wypełnij to pole';

	$('#change-player-name-form').validate({
		rules: {
			name: {
				required: true,
				minlength: nameMinLength,
				maxlength: nameMaxLength,
				pattern: /^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/,
				notEqual: $('#actualName').val()
			}
		},

		messages: {
			name: {
				required: fillCellMessage,
				minlength: 'Nazwa musi posiadać minimum '+nameMinLength+' znaków',
				maxlength: 'Nazwa może posiadać maximum '+nameMaxLength+' znaków',
				pattern: 'Nazwa zawiera niedozowolone znaki',
				notEqual: 'Nowa nazwa nie została wybrana'
			}
		}
	});
})();
