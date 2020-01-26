SOS.Movement = function(object, anim){
	this.object = object;
	this.anim = anim || new SOS.AnimationList;
	this.timer = new SOSEngine.Timer;
	this.step = 2;
	this.objectCollisionY =	this.objectCollisionX = this.lastObjectCollisionX = this.lastObjectCollisionY = true;
}

SOS.Movement.prototype.stand = function(){
	this.timer.set(function(){
		this.anim.play('stand');
		this.timer.clear();
	}.bind(this), 1);
}

SOS.Movement.prototype.sendMove = function(){
	if (SOS.me != null && this.object === SOS.me.view){
		SOS.synchro.query('move', {posX: SOS.me.view.position.x, posY: SOS.me.view.position.y});
		if (SOS.point.obj.is(':visible'))
			SOS.point.setPosition(SOS.me.view.position.x, SOS.me.view.position.y);
		SOS.ContextMenu.close();
	}
}

SOS.Movement.prototype.left = function(){
	this.turnLeft();
	if (this.canMoveLeft()){
		this.anim.play('run');
		for (let i = 0; i < this.step; ++i){
			if (this.canMoveLeft())
				this.object.setPosition(this.object.position.x - 1);
			else{
				this.stand();
				break;
			}
		}
		this.sendMove();
	}
}

SOS.Movement.prototype.right = function(){
	this.turnRight();
	if (this.canMoveRight()){
		this.anim.play('run');
		for (let i = 0; i < this.step; ++i){
			if (this.canMoveRight())
				this.object.setPosition(this.object.position.x + 1);
			else{
				this.stand();
				break;
			}
		}
		this.sendMove();
	}
}

SOS.Movement.prototype.top = function(){
	if (this.canMoveTop()){
		this.anim.play('run');
		for (let i = 0; i < this.step; ++i){
			if (this.canMoveTop())
				this.object.setPosition(null, this.object.position.y - 1);
			else{
				this.stand();
				break;
			}
		}
		this.sendMove();
	}
}

SOS.Movement.prototype.bottom = function(){
	if (this.canMoveBottom()){
		this.anim.play('run');
		for (let i = 0; i < this.step; ++i){
			if (this.canMoveBottom())
				this.object.setPosition(null, this.object.position.y + 1);
			else{
				this.stand();
				break;
			}
		}
		this.sendMove();
	}
}

SOS.Movement.prototype.canMoveLeft = function(){
	this.objectCollisionX = SOSEngine.GroupManipulator.each(SOS.view.Scene.static.objects, this.object.isCollisionLeft.bind(this.object));
	if (this.objectCollisionX !== true)	this.lastObjectCollisionX = this.objectCollisionX;
	return this.objectCollisionX === true && !SOS.view.Scene.isCollisionInBorderLeft(this.object);
}

SOS.Movement.prototype.canMoveRight = function(){
	this.objectCollisionX = SOSEngine.GroupManipulator.each(SOS.view.Scene.static.objects, this.object.isCollisionRight.bind(this.object));
	if (this.objectCollisionX !== true) this.lastObjectCollisionX = this.objectCollisionX;
	return this.objectCollisionX === true && !SOS.view.Scene.isCollisionInBorderRight(this.object);
}

SOS.Movement.prototype.canMoveTop = function(){
	this.objectCollisionY = SOSEngine.GroupManipulator.each(SOS.view.Scene.static.objects, this.object.isCollisionTop.bind(this.object));
	if (this.objectCollisionY !== true) this.lastObjectCollisionY = this.objectCollisionY;
	return this.objectCollisionY === true && !SOS.view.Scene.isCollisionInBorderTop(this.object);
}

SOS.Movement.prototype.canMoveBottom = function(){
 	this.objectCollisionY = SOSEngine.GroupManipulator.each(SOS.view.Scene.static.objects, this.object.isCollisionBottom.bind(this.object));
	if (this.objectCollisionY !== true) this.lastObjectCollisionY = this.objectCollisionY;
	return this.objectCollisionY === true && !SOS.view.Scene.isCollisionInBorderBottom(this.object);
}

SOS.Movement.prototype.turnLeft = function(){
	if (!this.anim.obj.hasClass('turnX'));
		this.anim.obj.addClass('turnX');
}

SOS.Movement.prototype.turnRight = function(){
	if (this.anim.obj.hasClass('turnX'));
		this.anim.obj.removeClass('turnX');
}
