SOSEngine.Object = function(width, height, x, y){
	this.obj = $('<div class="object"></div>');
	SOSEngine.ObjectManipulator.call(this);
	
	this.setSize(width, height);
	this.setPosition(x, y);
}
SOSEngine.Object.prototype = Object.create(SOSEngine.ObjectManipulator.prototype);

SOSEngine.Object.prototype.init = function(){
	this.setSize(this.width, this.height);
	this.setPosition(this.position.x, this.position.y);
}