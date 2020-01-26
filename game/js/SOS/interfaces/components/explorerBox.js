SOS.interfaces.ExplorerBox = function(options){
	this.obj = $('<div class="explorerBox"></div>').hide().appendTo($('#SOSEngine'));
	this.header = $('<header class="unselectable" unselectable="on"><p class="name"></p></header>').appendTo(this.obj);
	this.exitButton = $('<button type="button" class="exit icon icon-close"></button>').appendTo(this.header);
	this.content = $('<div class="content"></div>').appendTo(this.obj);
	this.footer = $('<footer></footer>').appendTo(this.obj);
	this.init(options);
}
SOS.interfaces.ExplorerBox.saveAble = true;

SOS.interfaces.ExplorerBox.prototype.init = function(options){
	this.obj.draggable({containment: '#SOSEngine', cursor: '../../imgs/cursors/cursor.png', handle: 'header', opacity: 0.8, stack: '.explorerBox', addClasses: false});
	this.obj.resizable({containment: '#SOSEngine', handles: 'all', minWidth: 210, minHeight: 110});

	options = options || {savePosition: false, saveSize: false, load: false};
	if (options.name !== undefined)
		this.obj.data('name', options.name);
	if (options.load){
		if (options.savePosition)
			this.loadPosition();
		if (options.saveSize)
			this.loadSize();
	}
	this.initEvents(options);
}

SOS.interfaces.ExplorerBox.prototype.initEvents = function(options){
	var scroll = new PerfectScrollbar(this.content[0],{
		handlers: ['click-rail', 'drag-thumb', 'wheel', 'touch']
	});

	this.obj.on('drag', function(){
		if (typeof this.onDrag == 'function')
			this.onDrag();
	}.bind(this));

	this.obj.on('dragstart', function(){
		if (typeof this.onDragStart == 'function')
			this.onDragStart();
	}.bind(this));

	this.obj.on('dragstop', function(){
		if (typeof this.onDragStop == 'function')
			this.onDragStop();

		if (options.savePosition)
			this.savePosition();
	}.bind(this));

	this.obj.on('resize', function(){
		scroll.update();

		if (typeof this.onResize == 'function')
			this.onResize();

		if (options.saveSize)
			this.saveSize();
	}.bind(this));

	this.obj.mousedown(function(){
		$('.explorerBox').removeClass('front');
		this.obj.addClass('front');
	}.bind(this));
	this.exitButton.click(this.close.bind(this));
}

SOS.interfaces.ExplorerBox.prototype.setContent = function(header, content, footer){
	if (header != undefined || header != null){
		if (header instanceof jQuery)
			this.header.find('.name').append(header);
		else this.header.find('.name').html(header);
	}

	if (content != undefined || content != null){
		if (content instanceof jQuery)
			this.content.append(content);
		else this.content.html(content);
	}

	if (footer != undefined || footer != null){
		if (footer instanceof jQuery)
			this.footer.append(footer);
		else this.footer.html(footer);
	}
}

SOS.interfaces.ExplorerBox.prototype.setSize = function(w, h){
	if (w != undefined || w != null)
		this.obj.width(w);

	if (h != undefined || h != null)
		this.obj.height(h);
}

SOS.interfaces.ExplorerBox.prototype.setPosition = function(x, y){
	if (typeof x == 'string' && typeof y == 'string'){
		this.obj.position({my: x, at: y, of: '#SOSEngine .window'});
		return;
	}

	if (x != undefined || x != null)
		this.obj.css('left', x);

	if (y != undefined || y != null)
		this.obj.css('top', y);
}

SOS.interfaces.ExplorerBox.prototype.setPositionFromObject = function(obj, x, y){
	var pos = obj.position();
	if (typeof x == 'string'){
		let cordX;
		x = x.toLowerCase();

		if (x.search('center') != -1)
			cordX = Math.abs(pos.left + obj.width()/2 - this.obj.width()/2);
		else if (x.search('inside') != -1){
			if (x.search('left') != -1)
				cordX = pos.left;
			else if (x.search('right') != -1)
				cordX = pos.left + obj.width() - this.obj.width();
		}
		else if (x.search('outside') != -1){
			if (x.search('left') != -1)
				cordX = pos.left - this.obj.width();
			else if (x.search('right') != -1)
				cordX = pos.left + obj.width();
		}

		let windowWidth = $('#SOSEngine .window').width();
		if (cordX < 0) cordX = 0;
		else if ((cordX + this.obj.width()) > windowWidth)
		 cordX = windowWidth - this.obj.width();
		this.setPosition(cordX);
	}

	if (typeof y == 'string'){
		let cordY;
		y = y.toLowerCase();

		if (y.search('center') != -1)
			cordY = Math.abs(pos.top + obj.height()/2 - this.obj.height()/2);
		else if (y.search('inside') != -1){
			if (y.search('top') != -1)
				cordY = pos.top;
			else if (y.search('bottom') != -1)
				cordY = pos.top + obj.height() - this.obj.height();
		}
		else if (y.search('outside') != -1){
			if (y.search('top') != -1)
				cordY = pos.top - this.obj.height();
			else if (y.search('bottom') != -1)
				cordY = pos.top + obj.height();
		}

		let windowHeight = $('#SOSEngine .window').height();
		if (cordY < 0) cordY = 0;
		else if ((cordY + this.obj.height()) > windowHeight)
		 cordY = windowHeight - this.obj.height();
		this.setPosition(null, cordY);
	}
}

SOS.interfaces.ExplorerBox.prototype.focus = function(){
	$('.explorerBox').removeClass('front');
	this.obj.addClass('front');
}

SOS.interfaces.ExplorerBox.prototype.open = function(callback){
	if (this.obj.is(':hidden')){
		let self = this;
		this.focus();
		this.obj.css('visibility', 'hidden').show('fade', 20, function(){
			if (typeof self.onOpen === 'function')
				self.onOpen();

			$(this).hide('fade', 20, function(){
				$(this).css('visibility', 'visible').show('drop');
			});
		});
	}
}

SOS.interfaces.ExplorerBox.prototype.toggle = function(){
	if (this.obj.is(':hidden'))
		this.open();
	else this.close();
}

SOS.interfaces.ExplorerBox.prototype.close = function(){
	if (this.obj.is(':visible')){
		this.obj.hide('drop', function(){
			if (typeof this.onClose === 'function')
				this.onClose();
		}.bind(this));
	}
}

SOS.interfaces.ExplorerBox.prototype.saveSize = {};
SOS.interfaces.ExplorerBox.prototype.loadSize = {};
(function(){
	var sizes = Cookies.getJSON('SizesOfExplorerBoxes') || {};

	SOS.interfaces.ExplorerBox.prototype.saveSize = function(){
		let name = this.obj.data('name');
		if (SOS.interfaces.ExplorerBox.saveAble && name !== undefined){
			sizes[name] = {width: this.obj.width(), height: this.obj.height()};
			Cookies.set('SizesOfExplorerBoxes', sizes, {expires: 365});
		}
	}

	SOS.interfaces.ExplorerBox.prototype.loadSize = function(){
		let size = sizes[this.obj.data('name')];
		if (size !== undefined)
			this.setSize(size.width, size.height);
	}
})();

SOS.interfaces.ExplorerBox.prototype.savePosition = {};
SOS.interfaces.ExplorerBox.prototype.loadPosition = {};
(function(){
	var positions = Cookies.getJSON('PositionsOfExplorerBoxes') || {};

	SOS.interfaces.ExplorerBox.prototype.savePosition = function(){
		let name = this.obj.data('name');
		if (SOS.interfaces.ExplorerBox.saveAble && name !== undefined){
			let pos = this.obj.position();
			positions[name] = {x: pos.left, y: pos.top};
			Cookies.set('PositionsOfExplorerBoxes', positions, {expires: 365});
		}
	}

	SOS.interfaces.ExplorerBox.prototype.loadPosition = function(){
		let position = positions[this.obj.data('name')];
		if (position !== undefined)
			this.setPosition(position.x, position.y);
	}
})();

SOS.interfaces.AlertBox = function(){
	SOS.interfaces.ExplorerBox.call(this);
}
SOS.interfaces.AlertBox.prototype = Object.create(SOS.interfaces.ExplorerBox.prototype);

SOS.interfaces.AlertBox.prototype.open = function(){
	SOS.interfaces.ExplorerBox.prototype.open.call(this);
	SOS.events.offMovement();
}

SOS.interfaces.AlertBox.prototype.close = function(){
	SOS.interfaces.ExplorerBox.prototype.close.call(this);
	SOS.events.onMovement();
}

SOS.interfaces.ConfirmBox = function(){
	SOS.interfaces.AlertBox.call(this);
	this.information = this.content.find('.information');
	this.buttonList = this.content.find('.buttonList');
}
SOS.interfaces.ConfirmBox.prototype = Object.create(SOS.interfaces.AlertBox.prototype);

SOS.interfaces.ConfirmBox.prototype.init = function(){
	SOS.interfaces.AlertBox.prototype.init.call(this);
	this.content.html('<div class="information"></div><hr><div class="buttonList"></div>');
	this.obj.addClass('confirmBox');
 	this.obj.resizable('disable');
}

SOS.interfaces.ConfirmBox.prototype.addButton = function(name, content, callback){
	name = name || '';
	content = content || 'OK';
	callback = callback || function(){};

	let self = this, button = $('<button role="button" class="button focus '+name+'">'+content+'</button>');
	button.on('click.explorerBox', function(){
		callback();
		self.close();
	});
 	this.buttonList.append(button);
}

SOS.interfaces.ConfirmBox.prototype.changeButton = function(name, content, callback){
	callback = callback || function(){};

	let self = this, button = this.buttonList.find('.'+name+':first');
	if (button.length > 0){
		button.html(content);
		button.off('click.explorerBox').on('click.explorerBox', function(){
			callback();
			self.close();
		});
	}
}

SOS.interfaces.ConfirmBox.prototype.removeButton = function(name){
 	this.buttonList.find('.'+name+':first').detach();
}

SOS.interfaces.ConfirmBox.prototype.setContent = function(header, content, footer){
	this.information.html(content);
	SOS.interfaces.AlertBox.prototype.setContent.call(this, header, null, footer);
}

SOS.interfaces.GroupOfExplorerBoxes = function(boxes){
	this.boxes = boxes;
}

SOS.interfaces.GroupOfExplorerBoxes.prototype.toggle = function(){
	var result = true;
	$.each(this.boxes, function(i, box){
		result = box.obj.is(':hidden');
		if (!result) return;
	});

	if (result) this.open();
	else this.close();
}

SOS.interfaces.GroupOfExplorerBoxes.prototype.open = function(){
	$.each(this.boxes, function(i, box){box.open();});
}

SOS.interfaces.GroupOfExplorerBoxes.prototype.close = function(){
	$.each(this.boxes, function(i, box){box.close();});
}

SOS.interfaces.NotificationBox = function(){
	this.obj = $('<div class="notificationBox"></div>').appendTo($('#SOSEngine'));
}

SOS.interfaces.NotificationBox.prototype.addNotify = function(content, duration, callback){
	let notify = $('<p>'+content+'</p>').hide().prependTo(this.obj);
	notify.toggle('drop', {direction: 'down'}, function(){
		notify.delay(duration || 7000).toggle('drop', {direction: 'down'}, function(){
			notify.detach();
			if (typeof callback == 'function')
				callback();
		});
	});
}
