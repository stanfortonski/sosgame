(function(){
	if ($.urlParam('login') != null){
		getLoginForm();
	}
	else if ($.urlParam('remind') != null){
		getRemindForm();
	}

	function recaptchaReload(){
		if (typeof $('#recaptcha')[0] !== 'undefined'){
			grecaptcha.render('recaptcha', {
				sitekey: '6LcgbhcUAAAAAGL1vYIfFTIUqfRoiUuGZNg3f3MZ'
			});
		}
	}

	function getLoginForm(){
		$.get('ajax/authorize.form.php', function(response){
			$.popupBox.content = response;
			$.popupBox.show();
			recaptchaReload();
		});
	}

	function getRemindForm(){
		$.get('ajax/remind.form.php', function(response){
			$.popupBox.content = response;
			$.popupBox.show();
			recaptchaReload();
		});
	}

	$(document).on('submit', '#remind-form', function(e){
		let form = $('#remind-form');
		$.popupBox.content = 'Proszę czekać...';
		$.popupBox.canExit = false;
		$.popupBox.show();

		e.preventDefault();
		$.post(form.attr('action'), form.serialize(), function(response){
			$.popupBox.content = response;
			$.popupBox.canExit = true;
			$.popupBox.show();
			recaptchaReload();
		});
	});

	$(document).on('click', '#remind-button', function(e){
		e.preventDefault();
		getRemindForm();
	});

	$(document).on('click', '.login-button', function(e){
		e.preventDefault();
		getLoginForm();
	});
})();
