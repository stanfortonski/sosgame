SOSEngine.GroupManipulator = function(){
	this.objects = [];
}

SOSEngine.GroupManipulator.prototype.setOnLayer = function(z){
	if (Number(parseFloat(z)) === z){
		this.layer = parseInt(z);
		this.obj.css('z-index', this.layer);
	}
}

SOSEngine.GroupManipulator.prototype.add = function(object){
	this.obj.append(object.obj);
	this.objects.push(object);
}

SOSEngine.GroupManipulator.prototype.initAll = function(){
	$.each(this.objects, function(i, object){
		if (object.obj.hasClass('group'))
			object.initAll();
		else object.init();
	});
}

SOSEngine.GroupManipulator.prototype.remove = function(index){
	this.objects[index].obj.remove();
	this.objects.splice(index, 1);
}

SOSEngine.GroupManipulator.prototype.clear = function(){
	this.obj.empty();
	this.objects.splice(0, this.objects.length);
}

SOSEngine.GroupManipulator.prototype.move = function(x, y){
	$.each(this.objects, function(i, object){
		if (object.obj.hasClass('group'))
			object.move(x, y);
		else object.setPosition(object.position.x+x, object.position.y+y);
	});
}

SOSEngine.GroupManipulator.prototype.rotate = function(deg){
	$.each(this.objects, function(i, object){
		object.rotate(deg);
	});
}

SOSEngine.GroupManipulator.prototype.round = function(round){
	$.each(this.objects, function(i, object){
		object.round(round);
	});
}

SOSEngine.GroupManipulator.prototype.turnX = function(){
	$.each(this.objects, function(i, object){
		object.turnX();
	});
}

SOSEngine.GroupManipulator.prototype.turnY = function(){
	$.each(this.objects, function(i, object){
		object.turnY();
	});
}

SOSEngine.GroupManipulator.prototype.animate = function(name, duration, callback){
	SOSEngine.groupAnimations.animate(this, name, duration, callback);
}

SOSEngine.GroupManipulator.each = function(objects, callback){
	let result = true;
	function each(toCheck){
		if (!result) return;
		$.each(toCheck, function(i, object){
			if (object.obj.hasClass('group')){
				each(object.objects);
			}
			else if (object.obj.hasClass('object')){
				if (callback(object)){
					result = object;
					return;
				}
			}
		});
	}
	each(objects);
	return result;
}
