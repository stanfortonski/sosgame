SOSEngine.ObjectManipulator = function(){
	this.position = {x: null, y: null};
	this.width = null;
	this.height = null;
}

SOSEngine.ObjectManipulator.prototype.setOnLayer = function(z){
	if (Number(parseFloat(z)) === z){
		this.layer = parseInt(z);
		this.obj.css('z-index', this.layer);
	}
}

SOSEngine.ObjectManipulator.prototype.setSize = function(w, h){
	if (Number(parseFloat(w)) === w){
		if (w < 0) w = -w;
		this.obj.width(Math.round(w*SOSEngine.scale));
		this.width = Math.round(w);
	}

	if (Number(parseFloat(h)) === h){
		if (h < 0) h = -h;
		this.obj.height(Math.round(h*SOSEngine.scale));
		this.height = Math.round(h);
	}
}

SOSEngine.ObjectManipulator.prototype.setPosition = function(x, y){
	if (Number(parseFloat(x)) === x){
		x = this.checkAndGetPositionX(x);
		this.position.x = x--;
		this.obj.css('left', x*SOSEngine.scale);
	}

	if (Number(parseFloat(y)) === y){
		y = this.checkAndGetPositionY(y);
		this.position.y = y--;
		this.obj.css('top', y*SOSEngine.scale);
	}
}

SOSEngine.ObjectManipulator.prototype.setPositionAnimate = function(x, y, duration, callback){
	let anim = {};
	if (Number(parseFloat(x)) === x){
		x = this.checkAndGetPositionX(x);
		this.position.x = x--;
		anim.left = x*SOSEngine.scale;
	}

	if (Number(parseFloat(y)) === y){
		y = this.checkAndGetPositionY(y);
		this.position.y = y--;
		anim.top = y*SOSEngine.scale;
	}
	this.obj.stop(true, true).animate(anim, duration || 1000, 'linear', callback);
}

SOSEngine.ObjectManipulator.prototype.checkAndGetPositionX = function(x){
	let scene = this.obj.parent();
	if (x <= 0) return 1;
	else if (scene.hasClass('scene')){
		let width = parseInt(scene.width()/SOSEngine.scale) - this.width+1;
		if (x >= width) return width;
	}
	return Math.round(x);
}

SOSEngine.ObjectManipulator.prototype.checkAndGetPositionY = function(y){
	let scene = this.obj.parent();
	if (y <= 0) return 1;
	else if (scene.hasClass('scene')){
		let height = parseInt(scene.height()/SOSEngine.scale) - this.height+1;
		if (y >= height) return height;
	}
	return Math.round(y);
}

SOSEngine.ObjectManipulator.prototype.round = function(rad){
	if (rad > 1) rad = 1;
	this.obj.css('border-radius', (rad*SOSEngine.scale*this.width/2)+'px');
}

SOSEngine.ObjectManipulator.prototype.rotate = function(deg){
	this.rotation = deg;
	let value = 'rotate('+deg+'deg)';

	this.obj.css({
		'-webkit-transform': value,
		'-moz-transform': value,
		'-o-transform': value,
		'transform': value
	});
}

SOSEngine.ObjectManipulator.prototype.isTurnedX = function(){
	return this.obj.hasClass('turnX');
}

SOSEngine.ObjectManipulator.prototype.isTurnedY = function(){
	return this.obj.hasClass('turnY');
}

SOSEngine.ObjectManipulator.prototype.turnX = function(){
	this.obj.toggleClass('turnX');
}

SOSEngine.ObjectManipulator.prototype.turnY = function(){
	this.obj.toggleClass('turnX');
}

SOSEngine.ObjectManipulator.prototype.setTexture = function(texture){
	this.obj.css('background', texture);
}

SOSEngine.ObjectManipulator.prototype.getTexture = function(texture){
	return this.obj.css('background');
}

SOSEngine.ObjectManipulator.prototype.getFirstPosition = function(){
	return {x: this.position.x, y: this.position.y};
}

SOSEngine.ObjectManipulator.prototype.getLastPosition = function(){
	return {x: this.position.x + this.width-1, y: this.position.y + this.height-1};
}

SOSEngine.ObjectManipulator.prototype.animate = function(name, duration, callback){
	SOSEngine.objectAnimations.animate(this, name, duration, callback);
}
