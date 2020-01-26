SOS.events.initInterface = function(){
	$(window).off('resize.SOSEngine');
	$(window).on('resize.SOSEngine', this.adjustInterfaceSize.bind(this));
	this.adjustInterfaceSize();
	this.onInterface();
	this.onOptions();
}

SOS.events.onInterface = function(){
	$(document).on('keydown.interface', this.interfaceKeyDown.bind(this));

	$.each(SOS.MainInterface.groups, function(index, grp){
		$('#'+index).on('click.interface', function(){grp.toggle()});
	});

	$.each(SOS.MainInterface.boxes, function(index, box){
		$('#'+index).on('click.interface', function(){box.toggle()});
	});
}

SOS.events.offInterface = function(){
	$(document).off('.interface');

	$.each(SOS.MainInterface.groups, function(index, grp){
		$('#'+index).off('.interface');
	});

	$.each(SOS.MainInterface.boxes, function(index, box){
		$('#'+index).off('.interface');
	});

	$('#mainInterface .rightMenu').off('click.interface');
	$('#mainInterface .footMenuBar').off('click.interface');
	$('#mainInterface .footMenuBar nav .button, #mainInterface .rightMenu .button').off('click.interface');
}

SOS.events.interfaceKeyDown = function(e){
	switch (e.keyCode || e.which){
		case 'm'.charCodeAt(0):
		case 'M'.charCodeAt(0):
			SOS.MainInterface.boxes.map.toggle();
		break;

		case 'g'.charCodeAt(0):
		case 'G'.charCodeAt(0):
			SOS.MainInterface.groups.team.toggle();
		break;

		case 'i'.charCodeAt(0):
		case 'I'.charCodeAt(0):
			SOS.MainInterface.boxes.eq.toggle();
		break;

		case 'o'.charCodeAt(0):
		case 'O'.charCodeAt(0):
			SOS.MainInterface.boxes.options.toggle();
		break;

		case 'u'.charCodeAt(0):
		case 'U'.charCodeAt(0):
			SOS.MainInterface.boxes.skills.toggle();
		break;

		case 't'.charCodeAt(0):
		case 'T'.charCodeAt(0):
			SOS.Chat.main.toggle();
		break;
	}
}

SOS.events.adjustInterfaceSize = function(e, engine){
	if (e === undefined || e == null);
	else if (e.target !== window) return;
	engine = engine || $('.SOSEngine:visible');

	let interface = engine.parent(),
		rightMenu = interface.find('.rightMenu');
		//footer = interface.find('.footer'),
		width = $(window).width();
		height = $(window).height();// - footer.height();

	engine.width(width).height(height);
	rightMenu.height(height);

	$('.explorerBox').css({'max-height': height, 'max-width': width});
	$('.explorerBox').each(function(){
		if ($(this).is(':hidden'))
			$(this).css('visibility', 'hidden').show();

			let ownWidth = $(this).width(),
				ownHeight = $(this).height(),
				pos = $(this).position();

			$(this).css('left', (pos.left*100/width)+'%');
			$(this).css('top', (pos.top*100/height)+'%');

			if (pos.left + ownWidth > width)
				$(this).css('left', width - ownWidth);
			else if (pos.left < 0) $(this).css('left', 0);

			if (pos.top + ownHeight > height)
				$(this).css('top', height - ownHeight);
			else if (pos.top < 0) $(this).css('top', 0);
	});

	SOS.MainInterface.initDefaultPositions();

	$('.explorerBox').each(function(){
		if ($(this).css('visibility') === 'hidden')
			$(this).css('visibility', 'visible').hide();
	});

	SOS.view.Window.resize();
	SOS.battleView.Window.resize();
}
