SOSEngine.StaticObject = function(jqueryObj){
	this.obj = jqueryObj;
	SOSEngine.ObjectManipulator.call(this);

	this.init();
}
SOSEngine.StaticObject.prototype = Object.create(SOSEngine.ObjectManipulator.prototype);

SOSEngine.StaticObject.prototype.init = function(){
	let data = this.obj.data();
	if (data.width !== undefined && data.height !== undefined){
		this.setSize(data.width, data.height);
	}
	else{
		this.width = parseInt(this.obj.width()/SOSEngine.scale);
		this.height = parseInt(this.obj.height()/SOSEngine.scale);
	}

	if (data.x !== undefined && data.y !== undefined){
		this.setPosition(data.x, data.y);
	}
	else this.setPosition(1, 1);

	if (data.texture !== undefined){
		this.setTexture(data.texture);
	}

	if (data.rotate !== undefined){
		this.rotate(data.rotate);
	}

	if (data.round !== undefined){
		this.round(data.round);
	}

	if (data.layer !== undefined){
		this.setOnLayer(data.layer);
	}

	if (data.turnx !== undefined){
		this.turnX();
	}

	if (data.turny !== undefined){
		this.turnY();
	}
}
