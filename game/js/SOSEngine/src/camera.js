SOSEngine.Camera = function(main){
	this.main = main;
	this.followed = null;
	this.follow = null;
	this.effects = new SOSEngine.CameraEffects(this);

	this.init();
}

SOSEngine.Camera.prototype.init = function(){
	if (this.followed != null)
		this.lookAt(this.followed);

	let center = this.getPositionCenterOfWindow();
	if (this.main.Scene.width < this.main.Window.width){
		this.main.Scene.setPosition(center.x);
	}
	if (this.main.Scene.height < this.main.Window.height){
		this.main.Scene.setPosition(null, center.y);
	}
}

SOSEngine.Camera.prototype.setPosition = function(x, y){
	if (!this.effects.isEffectInUse()){
		this.main.Scene.setPosition(-x, -y);
		this.follow = null;
	}
}

SOSEngine.Camera.prototype.setPositionAnimate = function(x, y, duration, callback){
	if (!this.effects.isEffectInUse()){
		this.main.Scene.setPositionAnimate(-x, -y, duration, callback);
		this.follow = null;
	}
}

SOSEngine.Camera.prototype.getPositionCenterOfWindow = function(){
	return {x: this.main.Window.width/2-this.main.Scene.width/2, y: this.main.Window.height/2-this.main.Scene.height/2};
}

SOSEngine.Camera.prototype.getSafeDistance = function(){
	return {x: parseInt(this.main.Window.width/2), y: parseInt(this.main.Window.height/2)};
}

SOSEngine.Camera.prototype.getFollowedPosition = {};
SOSEngine.Camera.prototype.lookAt = {};
SOSEngine.Camera.prototype.lookAtAnimate = {};
(function(){
	function getFollowedPositionX(){
		let x = -this.follow.position.x-(this.follow.width/2), safeDistance = this.getSafeDistance().x;
		if (this.main.Scene.isCollisionInBorderLeft(this.follow) || -x < safeDistance+1){
			x = 1;
		}
		else if (this.main.Scene.isCollisionInBorderRight(this.follow) || -x-safeDistance > this.main.Scene.width - this.main.Window.width){
			x = -this.main.Scene.width + this.main.Window.width+2;
		}
		else x += safeDistance+2;
		return x;
	}

	function getFollowedPositionY(){
		let y = -this.follow.position.y-(this.follow.height/2), safeDistance = this.getSafeDistance().y;
		if (this.main.Scene.isCollisionInBorderTop(this.follow) || -y < safeDistance+1){
			y = 1;
		}
		else if (this.main.Scene.isCollisionInBorderBottom(this.follow) || -y-safeDistance > this.main.Scene.height - this.main.Window.height){
			y = -this.main.Scene.height + this.main.Window.height+2;
		}
		else y += safeDistance+2;
		return y;
	}

	SOSEngine.Camera.prototype.getFollowedPosition = function(){
		return {x: getFollowedPositionX.call(this), y: getFollowedPositionY.call(this)};
	}

	SOSEngine.Camera.prototype.lookAt = function(object){
		this.follow = this.followed = object;

		let pos = this.getFollowedPosition(),
			sceneWider = this.main.Scene.width >= this.main.Window.width,
			sceneLonger = this.main.Scene.height >= this.main.Window.height;

		if (sceneWider && !sceneLonger){
			this.main.Scene.setPosition(pos.x);
		}
		else if (!sceneWider && sceneLonger){
			this.main.Scene.setPosition(null, pos.y);
		}
		else if (sceneWider || sceneLonger){
			this.main.Scene.setPosition(pos.x, pos.y);
		}
		else this.follow = null;
	}

	SOSEngine.Camera.prototype.lookAtAnimate = function(object, duration, callback){
		duration = duration || 200;
		this.follow = this.followed = object;
		let pos = this.getFollowedPosition(),
			sceneWider = this.main.Scene.width >= this.main.Window.width,
			sceneLonger = this.main.Scene.height >= this.main.Window.height;

		if (sceneWider && !sceneLonger){
			this.main.Scene.setPositionAnimate(pos.x, null, duration, callback);
		}
		else if (!sceneWider && sceneLonger){
			this.main.Scene.setPositionAnimate(null, pos.y, duration, callback);
		}
		else if (sceneWider || sceneLonger){
			this.main.Scene.setPositionAnimate(pos.x, pos.y, duration, callback);
		}
		else this.follow = null;
	}
})();
