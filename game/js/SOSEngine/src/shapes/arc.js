SOSEngine.shapes.Arc = function(radius, startAngle, endAngle, x, y){
	this.radius = null;
	this.startAngle = null;
	this.endAngle = null;
	this.position = {x: null, y: null};
	
	SOSEngine.Group.call(this);
	this.init(radius, startAngle, endAngle, x, y);
}
SOSEngine.shapes.Arc.prototype = Object.create(SOSEngine.Group.prototype);

SOSEngine.shapes.Arc.prototype.init = function(radius, startAngle, endAngle, x, y){
	this.setRadius(radius);
	this.setAngle(startAngle, endAngle);
	this.setPosition(x, y);
	this.make();
}

SOSEngine.shapes.Arc.prototype.setRadius = function(radius){
	if (Number(parseFloat(radius)) === radius && radius > 0)
		this.radius = Math.round(radius);
}

SOSEngine.shapes.Arc.prototype.setPosition = function(x, y){
	if (Number(parseFloat(x)) === x)
		this.position.x = Math.round(x);
	
	if (Number(parseFloat(y)) === y)
		this.position.y = Math.round(y);
}

SOSEngine.shapes.Arc.prototype.setAngle = function(startAngle, endAngle){
	if (Number(parseFloat(startAngle)) === startAngle)
		this.startAngle = Math.round(startAngle);
	
	if (Number(parseFloat(endAngle)) === endAngle)
		this.endAngle = Math.round(endAngle);
}

SOSEngine.shapes.Arc.prototype.make = function(){
	this.clear();
	let startAngle = this.degToRad(this.startAngle),
		endAngle = this.degToRad(this.endAngle),
		stepSize = (endAngle - startAngle)/(50*this.radius);
		
	for (let angle = startAngle; angle <= endAngle; angle += stepSize){
		this.add(new SOSEngine.Object(1, 1, 
			Math.sin(angle) * this.radius + this.position.x,
			-Math.cos(angle) * this.radius + this.position.y
		));
	}
}

SOSEngine.shapes.Arc.prototype.degToRad = function(deg){
	return deg * Math.PI / 180;
}