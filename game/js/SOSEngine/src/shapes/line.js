SOSEngine.shapes.Line = function(xFrom, yFrom, xTo, yTo, fat){
	this.from = {x: null, y: null};
	this.to = {x: null, y: null};
	this.fat = fat || 1;
	
	SOSEngine.Group.call(this);
	this.init(xFrom, yFrom, xTo, yTo, fat);
}
SOSEngine.shapes.Line.prototype = Object.create(SOSEngine.Group.prototype);

SOSEngine.shapes.Line.prototype.init = function(xFrom, yFrom, xTo, yTo, fat){
	this.setStartPosition(xFrom, yFrom);
	this.setEndPosition(xTo, yTo);
	this.setFat(fat);
	this.make();
}

SOSEngine.shapes.Line.prototype.setStartPosition = function(x, y){
	if (Number(parseFloat(x)) === x)
		this.from.x = Math.round(x);
	
	if (Number(parseFloat(y)) === y)
		this.from.y = Math.round(y);
}

SOSEngine.shapes.Line.prototype.setEndPosition = function(x, y){
	if (Number(parseFloat(x)) === x)
		this.to.x =	Math.round(x);
	
	if (Number(parseFloat(y)) === y)
		this.to.y = Math.round(y);
}

SOSEngine.shapes.Line.prototype.setFat = function(fat){
	if (Number(parseFloat(fat)) === fat && fat > 0)
		this.fat = Math.round(fat);
}

SOSEngine.shapes.Line.prototype.make = function(){
	this.clear();
	
	let xFrom = this.from.x, 
		yFrom = this.from.y,
		distX = Math.abs(this.to.x - xFrom),
		distY = Math.abs(this.to.y - yFrom),
		stepX = xFrom < this.to.x ? 1 : -1,
		stepY = yFrom < this.to.y ? 1 : -1,
		diff = distX - distY;
		
	function calcDiffX(){
		if (diff*2 < distX){
			diff += distX;
			yFrom += stepY;
		}
	}
		
	function calcDiffY(){
		if (diff*2 > -distY){
			diff -= distY;
			xFrom += stepX;
		}
	}	
		
    while (yFrom != this.to.y || xFrom != this.to.x){
 		this.add(new SOSEngine.Object(this.fat, this.fat, xFrom, yFrom));
		
		if (yFrom > xFrom){
			calcDiffY();
			calcDiffX();
		}
		else{
			calcDiffX();
			calcDiffY();
		}
	}
}