SOSEngine.Scene = function(main){
	this.main = main;
	this.obj = main.global.find('.scene');
	SOSEngine.StaticObject.call(this, this.obj);
	SOSEngine.GroupManipulator.call(this);

	this.loadStaticObjects();
}
SOSEngine.Scene.prototype = Object.create(SOSEngine.StaticObject.prototype);
Object.assign(SOSEngine.Scene.prototype, SOSEngine.GroupManipulator.prototype);
SOSEngine.Scene.prototype.constructor = SOSEngine.Scene;
SOSEngine.Scene.prototype.sceneRotate = SOSEngine.ObjectManipulator.prototype.rotate;
SOSEngine.Scene.prototype.sceneRound = SOSEngine.ObjectManipulator.prototype.round;
SOSEngine.Scene.prototype.sceneTurnX = SOSEngine.ObjectManipulator.prototype.turnX;
SOSEngine.Scene.prototype.sceneTurnY = SOSEngine.ObjectManipulator.prototype.turnY;
SOSEngine.Scene.prototype.sceneAnimate = SOSEngine.ObjectManipulator.prototype.animate;
SOSEngine.Scene.prototype.setOnLayer = null;

SOSEngine.Scene.prototype.loadStaticObjects = {};
(function(){
	var staticObjects = new SOSEngine.GroupManipulator,
		images = new SOSEngine.GroupManipulator;

	function loadStaticObjects(current, jqueryObj){
		jqueryObj.children().each(function(){
			if ($(this).hasClass('image')){
				images.objects.push(new SOSEngine.StaticImage($(this)));
			}
			else if ($(this).hasClass('object')){
				current.push(new SOSEngine.StaticObject($(this)));
			}
			else if ($(this).hasClass('group')){
				var group = new SOSEngine.StaticGroup($(this));
				loadStaticObjects(group.objects, $(this));
				current.push(group);
			}
		});
	}

	SOSEngine.Scene.prototype.loadStaticObjects = function(){
		loadStaticObjects(staticObjects.objects, this.obj);
		this.static = staticObjects;
		this.static.obj = this.obj;

		this.images = images;
		this.images.obj = this.obj;
	}
})();

SOSEngine.Scene.prototype.isCollisionInBorderTop = function(object){
	return object.position.y <= 1;
}

SOSEngine.Scene.prototype.isCollisionInBorderLeft = function(object){
	return object.position.x <= 1;
}

SOSEngine.Scene.prototype.isCollisionInBorderBottom = function(object){
	return object.getLastPosition().y >= this.height;
}

SOSEngine.Scene.prototype.isCollisionInBorderRight = function(object){
	return object.getLastPosition().x >= this.width;
}

SOSEngine.Scene.prototype.checkAndGetPositionX = function(x){
	return x == 0 ? 1 : Math.round(x);
}

SOSEngine.Scene.prototype.checkAndGetPositionY = function(y){
	return y == 0 ? 1 : Math.round(y);
}
