SOS.interfaces.ContextMenu = function(){
	this.obj = $('<div class="contextMenu unselectable" unselectable="on"></div>').appendTo($('body'));
	this.from = null;
	this.amount = 0;
	this.init();
}

SOS.interfaces.ContextMenu.prototype.init = function(){
	let self = this;
	this.setCursorPosition = SOS.events.getCursorPositionEventFunction(this.obj, function(pos){
		self.obj.css(pos);
	}, {left: 0, top: 15});

	$(document).on('click', '.contextMenu, .button:not(.withContextMenu), button:not(.withContextMenu), [role="button"]:not(.withContextMenu), input:not(.withContextMenu), label:not(.withContextMenu), select:not(.withContextMenu), a:not(.withContextMenu)', function(e){
		self.close();
	});
}

SOS.interfaces.ContextMenu.prototype.setList = {};
(function(){
	function generateList(options){
		this.obj.html('');
		$.each(options, function(index, option){
			let optionView = $('<p>'+option.name+'</p>');
			optionView.click(function(){
				option.click();
			});
			this.obj.append(optionView);
		}.bind(this));
	}

	SOS.interfaces.ContextMenu.prototype.setList = function(e, from, options){
		let empty = this.from == null, same = false;
		if (!empty) same = this.from[0] === from[0];

		generateList.call(this, options);

		if (empty || !same){
			if (!empty) this.from.removeClass('withContextMenu');
			this.from = from.addClass('withContextMenu');
			this.amount = options.length;
			return this.open(e);
		}
		return this.toggle(e);
	}
})();

SOS.interfaces.ContextMenu.prototype.toggle = function(e){
	if (this.obj.is(':hidden'))
		return this.open(e);
	return this.close();
}

SOS.interfaces.ContextMenu.prototype.open = function(e){
	if (e !== undefined)
		this.setCursorPosition(e);
	this.obj.hide().slideDown(this.amount * 30);
	return this;
}

SOS.interfaces.ContextMenu.prototype.close = function(){
	this.obj.slideUp(this.amount * 30);
	return this;
}
