SOSEngine.CameraEffects = function(camera){
	this.camera = camera;
	this.timer = new SOSEngine.Timer;
	this.isBlink = this.isVibration = false;

	this.start = this.camera.main.Scene.obj.fadeIn.bind(this.camera.main.Scene.obj);
	this.sleep = this.camera.main.Scene.obj.fadeOut.bind(this.camera.main.Scene.obj);
	this.toggle = this.camera.main.Scene.obj.fadeToggle.bind(this.camera.main.Scene.obj);
}

SOSEngine.CameraEffects.prototype.isEffectInUse = function(){
	return this.isVibration || this.isBlink;
}

SOSEngine.CameraEffects.prototype.blink = function(duration, delay){
	let self = this;
	this.isBlink = true;
	this.toggle(duration, function(){
		self.camera.main.Scene.obj.delay(delay || 1);
		self.toggle(duration, function(){
			self.isBlink = false;
		});
	});
}

SOSEngine.CameraEffects.prototype.vibration = function(power, length){
	power = power || 80;
	length = length || 1;
	this.isVibration = true;

	let self = this;
	function start(){
		let startPos, posX = -1 + Math.round(Math.random() * length) * 2,
			posY = -1 + Math.round(Math.random() * length) * 2;

		if (self.camera.follow != null)
			startPos = self.camera.getFollowedPosition();
		else startPos = self.camera.getPositionCenterOfWindow();

		self.camera.main.Scene.setPositionAnimate(startPos.x + posX, startPos.y + posY, power/2);
		if (self.isVibration)
			self.timer.set(start, power);
	}
	start();
}

SOSEngine.CameraEffects.prototype.vibrationOff = function(){
	if (this.isVibration){
		this.isVibration = false;
		this.camera.init();
		this.timer.clear();
	}
}
