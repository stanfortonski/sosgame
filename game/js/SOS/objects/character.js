SOS.Character = function(data){
	this.view = new SOSEngine.Object(12, 22, data.posX, data.posY);
	this.anim = new SOS.AnimationList(data.path);
	this.initCharacter(data);
}

SOS.Character.prototype.initCharacter = function(data){
	this.view.obj.attr({'data-id': data.id, 'data-name': data.name, 'data-lvl': data.lvl});
	this.view.obj.addClass('character').append(this.anim.obj);
}
