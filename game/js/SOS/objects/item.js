SOS.Item = function(data){
	this.icon = $('<div class="icon item"><div class="amount"></div></div>');
	this.view = new SOSEngine.Image(data.path, 10, 10, '100%', '100%', data.posX, data.posY);
	this.init(data);
}
SOS.Item.itemDragged = null;

SOS.Item.prototype.init = function(data){
	let self = this;

	this.icon.addClass('item-'+data.id_rank).css('background-image', 'url('+data.path+')').attr({'data-id': data.id, 'data-position': data.id_pos, 'data-stack': data.stack});
	this.icon.draggable({
		zIndex: 99999,
		opacity: 0.55,
		containment: '#SOSEngine', //slot.parents().eq(3)
		helper: function(){
			console.log(self.icon.offset().top);
			console.log(self.icon.clone());
			return self.icon.clone().css({position: 'absolute', 'z-index': 50000, width: '1em', height: '1em', 'font-size': self.icon.width()});
		},
		start: function(event, ui){
			SOS.Item.itemDragged = self;
			//ui.helper.detach();
			ui.helper.appendTo($('#SOSEngine'));
			//ui.helper.css('cssText', 'top: '+ui.offset.top+' !important; left: '+ui.offset.left+' !important;');
			console.log(ui);
		},
		drag: function(event, ui){
			//var func = SOS.events.getCursorPositionEventFunction(ui.helper, function(pos){ui.helper.css(pos);}, {left: 300});
			//func(event);
			//ui.helper.css('cssText', 'top: '+ui.offset.top+' !important; left: '+ui.offset.left+' !important;');
		}
	});

	this.view.obj.addClass('item').click(this.clickItemOnMap.bind(this));
	this.view.obj.dblclick(this.clickTake.bind(this));
	this.changeAmount(data.amount);
	SOS.items.add(this);
}

SOS.Item.prototype.changeAmount = function(amount){
	this.amount = amount;
	if (amount > 1)
		this.icon.find('.amount').html(amount);
	else this.icon.find('.amount').html('');
}

SOS.Item.prototype.clickItemOnMap = function(e){
	SOS.ContextMenu.setList(e, this.view.obj, [{
		name: 'Podnieś',
		click: this.clickTake.bind(this)
	}]);
	e.stopPropagation();
}

SOS.Item.prototype.clickItemOnEquipment = function(e){
	e.stopPropagation();
}

SOS.Item.prototype.clickTake = function(e){
	var data = this.view.obj.data(),
		eq = SOS.MainInterface.boxes.eq.content;

	SOS.me.move.to(this.view, function(){
		SOS.view.Camera.lookAtAnimate(SOS.me.view);
	}.bind(this), function(){
		this.view.obj.remove();
		SOS.synchro.query('item-take', {id: data.id, id_pos: data.position});
		eq.html(eq.html()+'<div>'+this.icon.html()+'</div>');
	}.bind(this));
	e.stopPropagation();
}
