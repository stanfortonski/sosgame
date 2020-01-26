(function(){
	let	maxLength = 1500;

	$('#profile-editor').validate({
		rules: {
			description: {
				maxlength: maxLength,
			}
		},

		messages: {
			description: {
				maxlength: 'Opis może posiadać maximum '+maxLength+' znaków',
			}
		}
	});


		$('#profile-avatar').validate({
			rules: {
				avatar: {
					required: true,
					accept: 'image/*',
					filesize: 2097152
				}
			},

			messages: {
				avatar: {
					required: 'Plik nie został wybrany',
					accept: 'Plik musi być obrazem',
					filesize: 'Plik nie może być większy niż 2MB'
				}
			}
		});
})();
