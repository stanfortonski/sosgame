SOSEngine.shapes.Cross = function(width, height, x, y, fat){
	this.width = null;
	this.height = null;
	this.position = {x: null, y: null};
	this.fat = fat || 1;

	SOSEngine.Group.call(this);
	this.init(width, height, x, y, fat);
}
SOSEngine.shapes.Cross.prototype = Object.create(SOSEngine.Group.prototype);

SOSEngine.shapes.Cross.prototype.init = function(width, height, x, y, fat){
	this.setSize(width, height);
	this.setPosition(x, y);
	this.setFat(fat);
	this.make();
}

SOSEngine.shapes.Cross.prototype.setSize = function(width, height){
	if (Number(parseFloat(width)) === width)
		this.width = Math.round(width);
	
	if (Number(parseFloat(height)) === height)
		this.height = Math.round(height);
}

SOSEngine.shapes.Cross.prototype.setPosition = function(x, y){
	if (Number(parseFloat(x)) === x)
		this.position.x = Math.round(x);
	
	if (Number(parseFloat(y)) === y)
		this.position.y = Math.round(y);
}

SOSEngine.shapes.Cross.prototype.setFat = function(fat){
	if (Number(parseFloat(fat)) === fat && fat > 0)
		this.fat = Math.round(fat);
}

SOSEngine.shapes.Cross.prototype.make = function(){
	this.clear();
	
	let width = Math.round(this.width/2),
		height = Math.round(this.height/2);
	
	this.add(new SOSEngine.Object(this.fat, this.fat, this.position.x, this.position.y));
	this.add(new SOSEngine.Object(width, this.fat, this.position.x + this.fat, this.position.y));
	this.add(new SOSEngine.Object(width, this.fat, this.position.x - width, this.position.y));
	this.add(new SOSEngine.Object(this.fat, height, this.position.x, this.position.y + this.fat));
	this.add(new SOSEngine.Object(this.fat, height, this.position.x, this.position.y - height));
}