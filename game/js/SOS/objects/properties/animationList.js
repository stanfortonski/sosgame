SOS.AnimationList = function(path){
	this.obj = $('<img src="">');
	this.imgPath = path || null;
	this.play('stand');
}

SOS.AnimationList.prototype.play = function(type){
	if (this.imgPath != null){
		if (this.obj.attr('src') != this.imgPath+SOS.AnimationList.anims[type]){
			this.obj.attr('src', this.imgPath+SOS.AnimationList.anims[type]);
		}
	}
}

SOS.AnimationList.prototype.get = function(){
	return this.obj.attr('src');
}

SOS.AnimationList.anims = {
	stand: 'stand.gif',
	run: 'run.gif',
	dead: 'dead.gif',
	attack: 'attack.gif'
};
