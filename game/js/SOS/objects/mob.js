SOS.Mob = function(data){
	SOS.Character.call(this, data);
	this.initMob(data);
}
SOS.Mob.prototype = Object.create(SOS.Character.prototype);

SOS.Mob.prototype.initMob = function(data){
	let self = this, image = new Image;
	image.src = this.anim.imgPath+'stand.gif';
	image.onload = function(){
		self.view.setSize(this.width/SOSEngine.scale, this.height/SOSEngine.scale);
	}

	this.view.obj.attr({'data-relations': data.relations});
	this.view.obj.addClass('mob').click(this.clickMob.bind(this));
	this.view.obj.dblclick(this.clickAttack.bind(this));

	SOS.mobs.add(this);
	SOS.interfaces.Title.add(this.view.obj, data.name+' '+data.lvl+' LVL');
}

SOS.Mob.prototype.clickMob = function(e){
	SOS.ContextMenu.setList(e, this.view.obj, [{
		name: 'Podgląd przeciwników',
		click: function(){}
	},{
		name: 'Zaatakuj',
		click: this.clickAttack.bind(this)
	}]);
	e.stopPropagation();
}

SOS.Mob.prototype.clickAttack = function(e){
	SOS.me.move.to(this.view, function(){
		SOS.view.Camera.lookAtAnimate(SOS.me.view);
	}, function(){
		SOS.battleSynchro.query('battle-init', {type: 'PVM', id: this.view.obj.data('id')});
	}.bind(this));
}
