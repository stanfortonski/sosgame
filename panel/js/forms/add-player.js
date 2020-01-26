var AddPlayerForm;
(function(){
	AddPlayerForm = function(){
		this.form = $('#add-general-form');
		this.section = {
			nameset: $('#nameSet'),
			races: $('#races'),
			presets:  $('#presets')
		};
		this.duration = 700;

		this.init();
	}

	AddPlayerForm.prototype.init = function(){
		var self = this;
		this.hideRaces();
		this.hidePresets();

		$(document).on('keyup', 'input#name',	this.checkNameAndServer.bind(this));
		$(document).on('change', 'select#server', function(e){
			self.checkNameAndServer();
			self.scrollToBottom();
		});
		$(document).on('blur', 'input#name', this.scrollToBottom.bind(this));
		$(document).on('change', 'input[name="race"]', this.checkRace.bind(this));
		$(document).on('change', 'input[name="char"]', this.scrollToBottom.bind(this));
		this.form.on('submit', function(e){
			e.preventDefault();
			self.submit();
		});
	}

	AddPlayerForm.prototype.checkNameAndServer = function(){
		var error = false;
		if (this.form.valid()){
			$.ajax({
				type: 'GET',
				url: 'ajax/can-add-player',
				dataType: 'json',
				data: {
					name: $('input#name').val(),
					server: $('select#server').val()
				}
			}).done(function(response){
				this.section.nameset.find('.error-box').remove();
				if (response.error !== undefined){
					this.section.nameset.append('<div class="error-box alert alert-danger" role="alert">'+response.error+'</div>');
					error = true;
				}
				else{
					this.section.races.find('input[type="radio"]').eq(0).prop("checked", true).prop("checked", false);
					this.section.races.parent().fadeIn(this.duration);
					this.isBlocked = true;
				}
			}.bind(this));
		}
		else error = true;

		if (error){
			this.hideRaces();
			this.hidePresets();
		}
	}

	AddPlayerForm.prototype.checkRace = function(){
		if (this.form.valid()){
			let race = this.section.races.find(':checked').val();

			$.get('ajax/get-presets', {race: race}, function(response){
				this.hidePresets();
				if (response.length != ""){
					this.section.presets.html(response).parent().slideDown(this.duration);
					this.scrollToBottom();
				}
			}.bind(this));
		}
	}

	AddPlayerForm.prototype.submit = function(){
		if (this.form.valid()){
			$.ajax({
				type: 'POST',
				url: this.form.attr('action'),
				data: this.form.serialize(),
				dataType: 'json'
			}).done(function(response){
				if (response.message !== undefined){
					Cookies.set('message', response.message, {domain: '.sosgame.online'});
					document.location.href = 'player-manager';
				}
				else{
					$.popupBox.content = response.error;
					$.popupBox.show();
				}
			});
		}
	}

	AddPlayerForm.prototype.scrollToBottom = function(){
			if (this.form.valid()){
				$('html, body').animate({
					scrollTop: document.body.scrollHeight
				}, this.duration);
			}
		}

	AddPlayerForm.prototype.hideRaces = function(){
		this.section.races.parent().hide();
	}

	AddPlayerForm.prototype.hidePresets = function(){
		this.section.presets.parent().hide();
	}

	AddPlayerForm = new AddPlayerForm;
})();
