SOS.MovementExtended = function(object, anim){
	SOS.Movement.call(this, object, anim);
	this.oldStep = this.step;
	this.destination = null;
	this.shadow = new SOSEngine.Object(this.object.width, this.object.height);
	this.fastMethod = false;
	this.strict = true;
	SOS.view.Scene.add(this.shadow);
}
SOS.MovementExtended.prototype = Object.create(SOS.Movement.prototype);
SOS.MovementExtended.delay = 14;

SOS.MovementExtended.prototype.init = function(object){
	this.destination = object;
	this.step = 1;
	this.isCollisionX = this.isCollisionY = 0;
	this.directionX = this.object.isObjectOnRight(this.destination) ? 1 : -1;
	this.directionY = this.object.isObjectOnBottom(this.destination) ? 1 : -1;

	this.destination.obj.off('click').on('click', function(e){e.stopPropagation()});
	this.shadow.obj.off('click').on('click', function(e){e.stopPropagation()});

	if (this.directionX == 1){
		this.canMoveX = this.canMoveRight;
		this.moveX = this.right;
	}
	else{
		this.canMoveX = this.canMoveLeft;
		this.moveX = this.left;
	}

	if (this.directionY == 1){
		this.canMoveY = this.canMoveBottom;
		this.moveY = this.bottom;
	}
	else{
		this.canMoveY = this.canMoveTop;
		this.moveY = this.top;
	}
}

SOS.MovementExtended.prototype.goTo = {};
(function(){
	var callback;
	SOS.MovementExtended.prototype.goTo = function(object, func){
		callback = func;
		this.init(object);
		let way = this.chooseStartDirection();

		if (this.fastMethod){
			if (way == 'x')
				fastGoX.call(this);
			else fastGoY.call(this);
		}
		else{
			if (way == 'x')
				goX.call(this);
			else goY.call(this);
		}
	}

	function goX(){
		if (compareMethodX.call(this)){
			this.moveX();
			this.timer.set(goX.bind(this), SOS.MovementExtended.delay);
		}
		else{
			++this.isCollisionX;
			if (this.isCollisionY < 2)
				this.timer.set(goY.bind(this), SOS.MovementExtended.delay);
			else this.stop(callback);
		}
	}

	function goY(){
		if (compareMethodY.call(this)){
			this.moveY();
			this.timer.set(goY.bind(this), SOS.MovementExtended.delay);
		}
		else{
			++this.isCollisionY;
			if (this.isCollisionX < 2)
				this.timer.set(goX.bind(this), SOS.MovementExtended.delay);
			else this.stop(callback);
		}
	}

	function fastGoX(){
		if (compareMethodX.call(this)){
			this.moveX();
			fastGoX.call(this);
		}
		else{
			++this.isCollisionX;
			if (this.isCollisionY < 2)
				fastGoY.call(this);
			else this.stop(callback);
		}
	}

	function fastGoY(){
		if (compareMethodY.call(this)){
			this.moveY();
			fastGoY.call(this);
		}
		else{
			++this.isCollisionY;
			if (this.isCollisionX < 2)
				fastGoX.call(this);
			else this.stop(callback);
		}
	}

	function compareMethodX(){
		var can = this.object.position.x != this.destination.position.x;
		if (this.canMoveY() && !this.strict)
			can = this.object.calcAndGetDistanceBetween(this.destination).x != 0;
		return this.canMoveX() && can;
	}

	function compareMethodY(){
		var can = this.object.position.y != this.destination.position.y;
		if (this.canMoveX() && !this.strict)
			can = this.object.calcAndGetDistanceBetween(this.destination).y != 0;
		return this.canMoveY() && can;
	}
})();

SOS.MovementExtended.prototype.chooseStartDirection = function(){
	let travel = new SOS.MovementExtended(this.shadow), countX = 0, countY = 0;
	this.shadow.setPosition(this.object.position.x, this.object.position.y);
	travel.init(this.destination);

	while (travel.canMoveX()){
		if (travel.object.position.x == this.destination.position.x) break;
		travel.moveX();
		countX += travel.directionX;
	}

	while (travel.canMoveY()){
		if (travel.object.position.y == this.destination.position.y) break;
		travel.moveY();
		countX += travel.directionY;
	}

	this.shadow.setPosition(this.object.position.x, this.object.position.y);

	while (travel.canMoveY()){
		if (travel.object.position.y == this.destination.position.y) break;
		travel.moveY();
		countY += travel.directionX;
	}

	while (travel.canMoveX()){
		if (travel.object.position.x == this.destination.position.x) break;
		travel.moveX();
		countY += travel.directionY;
	}

	this.shadow.setPosition(0, 0);

	if (countY == 0 && countX == 0) return null;
	else if (Math.abs(countX) > Math.abs(countY)) return 'x';
	return 'y';
}

SOS.MovementExtended.prototype.stop = function(callback){
	this.timer.clear();
	this.step = this.oldStep;
	this.stand();

	if (typeof callback === 'function')
		callback();

	this.destination = null;
}
