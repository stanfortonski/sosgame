SOS.interfaces.Main = function(){
	this.obj = $('#mainInterface');

	this.boxes = {
		map: new SOS.interfaces.ExplorerBox({name: 'map', savePosition: true, saveSize: true}),
		teamMain: new SOS.interfaces.ExplorerBox({name: 'team', savePosition: true, saveSize: true}),
		skills: new SOS.interfaces.ExplorerBox({name: 'skills', savePosition: true, saveSize: true}),
		eq: new SOS.interfaces.ExplorerBox({name: 'eq', savePosition: true, saveSize: true}),
		chat: SOS.Chat.main,
		exit: new SOS.interfaces.ConfirmBox,
		options: new SOS.interfaces.ExplorerBox,
		heroStats: new SOS.interfaces.ExplorerBox,
		heroEquipment: new SOS.interfaces.ExplorerBox,
		teamReserve: new SOS.interfaces.ExplorerBox,
		advertisement: new SOS.interfaces.ExplorerBox
	};

	this.groups = {
		team: new SOS.interfaces.GroupOfExplorerBoxes([this.boxes.teamMain, this.boxes.teamReserve]),
		hero: new SOS.interfaces.GroupOfExplorerBoxes([this.boxes.heroEquipment, this.boxes.heroStats])
	};

	this.init();
}

SOS.interfaces.Main.prototype.init = function(){
	this.boxes.advertisement.obj.css('z-index', 20000);
	this.boxes.heroStats.id = null;

	let scroll = new PerfectScrollbar(this.obj.find('.rightMenu')[0],{
		handlers: ['click-rail', 'drag-thumb', 'wheel', 'touch']
	}), scrollFooter = new PerfectScrollbar(this.obj.find('.footer nav')[0],{
		handlers: ['click-rail', 'drag-thumb', 'wheel', 'touch']
	});

	this.initContents();
	this.initEvents();
	this.initSizes();

	let cookie = Cookies.get('advertisement');
	if (cookie === undefined)
		Cookies.set('advertisement', false);

	if (!cookie)
		this.boxes.advertisement.open();
}

SOS.interfaces.Main.prototype.initContents = function(){
	let contents = $('#contents'),
			pvp  = SOS.canPVP ? '<span data-title="PVP zawsze dozwolone">Strefa walk</span>' : '<span data-title="PVP dozwolone za zgodą">Strefa pokojowa</span>';
	$('#worldInfo').html('<div>'+pvp+'</div><div>'+$('#startData').data('mapname')+'</div>');

	SOS.equipment.generateTable();
	SOS.equipment.generateTable();
	SOS.equipment.generateTable();

	this.boxes.eq.setContent('Ekwipunek', SOS.equipment.main.prepareAndGet());
	this.boxes.teamReserve.setContent('Rezerwa');
	this.boxes.exit.setContent('Wyjście', 'Czy napewno chcesz opuścić grę?');
	this.boxes.map.setContent('Mapa', $('#miniMap'));
	this.boxes.skills.setContent('Umiejęności', contents.find('.skills').html());
	this.boxes.options.setContent('Opcje', contents.find('.options').html());
	this.boxes.teamMain.setContent('Drużyna', contents.find('.team').html());
	this.boxes.teamMain.content.find('.teamPlace').append(SOS.teamNet.table);
	this.boxes.heroEquipment.setContent(null, contents.find('.heroEquipment'));
	this.boxes.advertisement.setContent('Reklama');

	this.boxes.exit.addButton('exit', 'Wyjdź', function(){document.location.href = 'index';});
	this.boxes.exit.addButton('cancel', 'Anuluj');

	SOS.interfaces.Title.add(this.boxes.options.exitButton, 'Zapisz');
}

SOS.interfaces.Main.prototype.initEvents = function(){
	this.boxes.map.onOpen = function(){
		this.boxes.map.onResize();

		if (SOS.me != null)
			SOS.point.setPosition(SOS.me.view.position.x, SOS.me.view.position.y);
	}.bind(this);

	this.boxes.map.onResize = function(){
		SOS.miniMapView.Window.init();
		SOS.miniMapView.Camera.init();
	}

	this.boxes.options.onClose = function(){
		SOS.Config.change();
	}

	this.boxes.teamMain.onOpen = function(){
		this.boxes.heroStats.setPositionFromObject(this.boxes.teamMain.obj, 'outside right', 'inside top');
		this.boxes.heroStats.onOpen();
	}.bind(this);

	this.boxes.heroStats.onOpen = function(){
		this.boxes.heroEquipment.setPositionFromObject(this.boxes.heroStats.obj, 'outside right', 'inside top');
		this.boxes.heroEquipment.obj.addClass('front');
	}.bind(this);

	this.boxes.teamReserve.onOpen = function(){
		this.boxes.teamReserve.setPositionFromObject(this.boxes.teamMain.obj, 'outside right', 'inside top');
		this.boxes.teamReserve.obj.addClass('front');
	}.bind(this);

	this.boxes.heroStats.onResize = this.boxes.heroStats.onDrag = this.boxes.heroStats.onOpen;
	this.boxes.teamMain.onResize = this.boxes.teamMain.onDrag = this.boxes.teamReserve.onOpen;

	this.boxes.advertisement.onClose = function(){
		Cookies.set('advertisement', true);
	}
}

SOS.interfaces.Main.prototype.initSizes = function(){
	if (SOS.isMobile)
		this.boxes.map.obj.css({width: 220, height: 220}).resizable({disabled: true});
	else this.boxes.map.obj.css({width: 260, height: 260}).resizable({maxWidth: 260, maxHeight: 260, minWidth: 200, minHeight: 200});

	this.boxes.map.header.hide();
	this.boxes.map.footer.hide();
	this.boxes.map.obj.addClass('scrollReset');
	this.boxes.map.obj.draggable('option', 'handle', '.content');
	this.boxes.map.content.css({padding: 0, height: '100%'});

	this.boxes.eq.obj.css({width: 450, height: 500}).resizable({maxWidth: 450, maxHeight: 500, minHeight: 380, minWidth: 340});
	this.boxes.options.obj.css({width: '65%', height: 450}).resizable('disable');
	this.boxes.teamMain.obj.css({width: 350, height: '100%'}).resizable({maxWidth: 350});
	this.boxes.heroStats.obj.css({width: 465}).resizable({maxWidth: 465});
	this.boxes.heroEquipment.obj.css({width: 230, height: 260}).resizable({maxWidth: 230, maxHeight: 260});
	this.boxes.advertisement.obj.css({height: 140, width: '100%'}).resizable({minHeight: 140, maxHeight: 140, disabled: true});
	this.boxes.chat.obj.css({width: 400});
}

SOS.interfaces.Main.prototype.initPositions = function(){
	if (Cookies.get('PositionsOfExplorerBoxes') === undefined){
		this.boxes.map.setPosition('right top', 'right top');
		this.boxes.teamMain.setPosition('left top', 'left top');
		this.boxes.eq.setPosition('right bottom', 'right bottom');
		this.boxes.chat.setPosition('left top', 'left top');
	}
}

SOS.interfaces.Main.prototype.initDefaultPositions = function(){
	this.initPositions();
	this.boxes.options.setPosition('center', 'center');
	this.boxes.exit.setPosition('center', 'center');
	this.boxes.advertisement.setPosition('center bottom', 'center bottom');
	SOS.Chat.friendBox.setPosition('center bottom', 'center bottom');
	SOS.Chat.offerBox.setPosition('center', 'center');
	this.boxes.chat.onOpen();
	this.boxes.teamMain.onOpen();
	this.boxes.teamReserve.onOpen();
	this.boxes.heroStats.onOpen();
}

SOS.interfaces.Main.prototype.loadPositionsAndSizes = function(){
	$.each(this.boxes, function(i, box){
		box.loadSize();
		box.loadPosition();
	});
}

SOS.interfaces.Main.prototype.initMiniMap = function(){
	let width = this.boxes.map.obj.resizable('option', 'maxWidth'),
		scaleX = width/(SOS.miniMapView.Scene.width*SOSEngine.scale),
		scaleY = width/(SOS.miniMapView.Scene.height*SOSEngine.scale);

	if (scaleX > 1) scaleX = 1;
 	if (scaleY > 1) scaleY = 1;

	let scale = 'scale('+scaleX+','+scaleY+')';
	$('#miniMap .scene').css({'-webkit-transform': scale, '-moz-transform': scale, '-o-transform': scale, transform: scale});

	SOS.point = new SOSEngine.Object(SOS.me.view.width, SOS.me.view.height, 1, 1);
	SOS.point.obj.addClass('pointer').append('<div class="blinkExt icon icon-red-x"></div>');

	let fontSize = SOS.me.view.width*SOS.me.view.height*(1.25-scaleX)*(1.25-scaleY);
	SOS.point.obj.find('.blinkExt').css('font-size', fontSize);

	SOS.miniMapView.Scene.add(SOS.point);

	scale = 'scale(1)';
	if (fontSize*scaleX < 20){
		scale = 'scale(1.4)';
	}
	else if (fontSize*scaleX > 50){
		scale = 'scale(.55)';
	}
	SOS.point.obj.css({'-webkit-transform': scale, '-moz-transform': scale, '-o-transform': scale, transform: scale});
}
