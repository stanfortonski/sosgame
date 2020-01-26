SOS.events.onOptions = function(){
	let musicSlider = $('#musicVolume'),
		handle = musicSlider.find('.ui-slider-handle');

	musicSlider.slider({
		value: SOS.Config.soundVolume,
		create: function(){
			handle.text(SOS.Config.soundVolume);
		},
		slide: function(event, ui){
			handle.text($(this).slider('value'));
			SOS.Config.changeSoundVolume(ui.value);
		},
		change: function(event, ui){
			handle.text($(this).slider('value'));
			SOS.Config.changeSoundVolume(ui.value);
		}
	});

	let effectSlider = $('#effectVolume'),
		handleEffect = effectSlider.find('.ui-slider-handle');

	effectSlider.slider({
		value: SOS.Config.effectVolume,
		create: function(){
			handleEffect.text(SOS.Config.effectVolume);
		},
		slide: function(event, ui){
			handleEffect.text($(this).slider('value'));
			SOS.Config.changeEffectVolume(ui.value);
		},
		change: function(event, ui){
			handleEffect.text($(this).slider('value'));
			SOS.Config.changeEffectVolume(ui.value);
		}
	});

	$('#soundMute').prop('checked', SOS.Config.soundMute).change(function(e){
		if (this.checked){
			SOS.Audio.canPlay = false;
			SOS.backgroundMusic.pause();
			musicSlider.slider('disable');
			effectSlider.slider('disable');
		}
		else{
			SOS.Audio.canPlay = true;
			SOS.backgroundMusic.play();
			musicSlider.slider('enable');
			effectSlider.slider('enable');
		}

		if (SOS.Config.soundMute != this.checked){
			SOS.Config.soundMute = this.checked;
		}
	}).trigger('change');

	$('#chatMutePm').prop('checked', SOS.Config.chatMutePm).change(function(){
		SOS.Config.chatMutePm = this.checked;
	});

	$('#chatMute').prop('checked', SOS.Config.chatMute).change(function(){
		SOS.Config.chatMute = this.checked;
		SOS.Chat.allowOffInformation = true;
		$('#chatMutePm').prop('disabled', this.checked).toggleClass('focus');
	});

	$('#saveInterface').prop('checked', !SOS.Config.saveInterface).change(function(){
		SOS.interfaces.ExplorerBox.saveAble = SOS.Config.saveInterface = !this.checked;
	});

	$('#pvpAble').prop('checked', SOS.Config.pvpAble).change(function(){
		SOS.Config.pvpAble = this.checked;
	});

	$('#unsetInvitations').prop('checked', SOS.Config.unsetInvitations).change(function(){
		SOS.Config.unsetInvitations = this.checked;
	});

	$('#resetInterface').click(function(){
		Cookies.remove('PositionsOfExplorerBoxes');
		Cookies.remove('SizesOfExplorerBoxes');
	});
}
