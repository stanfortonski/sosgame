SOSEngine.StaticGroup = function(jqueryObj){
	this.obj = jqueryObj;
	SOSEngine.GroupManipulator.call(this);
	
	let data = this.obj.data();
	if (data.layer !== undefined)
		this.setOnLayer(data.layer);
}
SOSEngine.StaticGroup.prototype = Object.create(SOSEngine.GroupManipulator.prototype);