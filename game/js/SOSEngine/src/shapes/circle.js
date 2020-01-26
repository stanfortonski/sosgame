SOSEngine.shapes.Circle = function(radius, x, y){
	SOSEngine.shapes.Arc.call(this, radius, 0, 360, x, y);
}
SOSEngine.shapes.Circle.prototype = Object.create(SOSEngine.shapes.Arc.prototype);

SOSEngine.shapes.FilledCircle = function(radius, x, y){
	SOSEngine.Group.call(this);
	this.init(radius, x, y);
}
SOSEngine.shapes.FilledCircle.prototype = Object.create(SOSEngine.Group.prototype);

SOSEngine.shapes.FilledCircle.prototype.init = function(radius, x, y){
	this.clear();
	while (radius >= 0){	
		this.add(new SOSEngine.shapes.Circle(radius--, x, y));
	}
}