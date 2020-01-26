SOSEngine.Group = function(layer){
	this.obj = $('<div class="group"></div>');
	SOSEngine.GroupManipulator.call(this);
	this.setOnLayer(layer);
}
SOSEngine.Group.prototype = Object.create(SOSEngine.GroupManipulator.prototype);