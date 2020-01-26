SOS.Routing = function(){
	this.timer = new SOSEngine.Timer;
	this.destination = new SOSEngine.Object;
	this.object = new SOSEngine.Object;
	this.point = new SOSEngine.Object;
	this.movement = new SOS.MovementExtended(this.object);
	this.movement.fastMethod = true;

	SOS.view.Scene.add(this.point);
	SOS.view.Scene.add(this.object);
	SOS.view.Scene.add(this.destination);
}

SOS.Routing.prototype.init = function(object, destination){
	this.timer.clear();
	this.startPoints = [];
	this.ways = [];
	this.isFound = this.reverse = false;
	this.isBottom = this.isRight = null;
	this.closerTop = this.closerBottom = this.closerLeft = this.closerRight = 0;
	this.counterX = this.counterY = 0;
	this.complexion = 0;

	this.destination.obj.off('click').on('click', function(e){e.stopPropagation();});
	this.object.obj.off('click').on('click', function(e){e.stopPropagation();});
	this.point.obj.off('click').on('click', function(e){e.stopPropagation();});

	this.movement.object = this.object;
	this.movement.lastObjectCollisionX = this.movement.lastObjectCollisionY = true;

	this.object.setSize(object.width, object.height);
	this.object.setPosition(object.position.x, object.position.y);
	this.destination.setSize(destination.width, destination.height);
	this.destination.setPosition(destination.position.x, destination.position.y);
	this.point.setSize(destination.width, destination.height);
	this.point.setPosition(object.position.x, object.position.y);
}

SOS.Routing.prototype.makeTableRouting = function(){
	var i = 0, limit = 2000;
	if (this.movement.fastMethod) limit = 50;

	this.movement.goTo(this.destination);
	main.call(this);
	function main(){
		let isEnd = this.isSuccessfulEndOfRoute(this.destination);
		++i;

		if (this.isSuccessfulEndOfRoute(this.point))
			this.applyPath(isEnd);
		else if (this.movement.destination == null)
			this.standardFindPath();

		if (isEnd){
			this.isFound = true;
			if (this.startPoints.length > 0)
				this.ways.splice(0, 0, this.startPoints[this.startPoints.length-1]);
			this.ways.push({x: this.destination.position.x,  y: this.destination.position.y});
		}
		else if (i <= limit)
			this.timer.set(main.bind(this), 1);
	}
}

SOS.Routing.prototype.applyPath = function(isEnd){
	if (this.ways.length > 0){
		let lastPoint = this.ways[this.ways.length-1];

		if (this.complexion > 0){
			this.complexion = 0;
			if (this.startPoints.length > 0){
				this.ways = [];
				this.ways.push(lastPoint);
			}
			this.movement.lastObjectCollisionY = this.movement.lastObjectCollisionX = true;
		}

		if (lastPoint.x == this.point.position.x && lastPoint.y == this.point.position.y){
			++this.complexion;
			this.point.setPosition(this.destination.position.x, this.destination.position.y);
		}
		else if (Math.abs(lastPoint.x - this.point.position.x) == 1 || Math.abs(lastPoint.y - this.point.position.y) == 1){
			this.ways.pop();
			this.ways.push({x: this.point.position.x,  y: this.point.position.y});
		}
		else this.ways.push({x: this.point.position.x,  y: this.point.position.y});
	}
	else this.ways.push({x: this.point.position.x,  y: this.point.position.y});

	this.counterX = this.counterY = 0;

	if (!isEnd)
		this.movement.goTo(this.destination);
}

SOS.Routing.prototype.standardFindPath = function(){
	this.reverse = false;
	this.movement.init(this.destination);
	this.movement.canMoveY();
	this.movement.canMoveX();

	if (this.movement.objectCollisionY !== true){
		this.calcClosersX();
		this.setAxisXOfPoint();
	}
	else this.setAxisYOfPointWhenIsBorder();

	if (this.movement.objectCollisionX !== true){
		this.calcClosersY();
		this.setAxisYOfPoint();
	}
	else this.setAxisXOfPointWhenIsBorder();

	let isCollision = this.movement.lastObjectCollisionX !== true && this.movement.lastObjectCollisionY !== true;
	if ((this.complexion > 0 && isCollision) || isCollision)
		this.complexionFindPath();

	this.movement.goTo(this.point);
}

SOS.Routing.prototype.complexionFindPath = function(){
	this.reverse = true;
	++this.complexion;
	this.movement.objectCollisionY = this.movement.lastObjectCollisionY;
	this.movement.objectCollisionX = this.movement.lastObjectCollisionX;

	this.calcDirections();

	let sumX = this.isRight + this.movement.directionX,
		sumY = this.isBottom + this.movement.directionY;

	if (this.startPoints.length > 2){
		this.reverse = false;
	}

	switch (sumX){
		case 0:
		case 2:
		case -2:
			this.object.setPosition(this.movement.objectCollisionY.position.x - this.object.width);
			this.setAxisYOfPoint();
		break;
	}

	switch (sumY){
		case -2:
			this.object.setPosition(null, this.movement.objectCollisionX.position.y - this.object.height);
			this.setAxisXOfPoint();
		break;

		case 0:
		case 2:
			this.object.setPosition(null, this.movement.objectCollisionX.position.y + this.movement.objectCollisionX.height + this.object.height);
			this.setAxisXOfPoint();
		break;
	}

	this.startPoints.push({x: this.object.position.x, y: this.object.position.y});
}

SOS.Routing.prototype.calcClosersY = function(){
	this.closerTop = this.object.position.y - this.movement.objectCollisionX.position.y;
	this.closerBottom = this.movement.objectCollisionX.position.y + this.movement.objectCollisionX.height - this.object.position.y;
}

SOS.Routing.prototype.calcClosersX = function(){
	this.closerLeft = this.object.position.x - this.movement.objectCollisionY.position.x;
	this.closerRight = this.movement.objectCollisionY.position.x + this.movement.objectCollisionY.width - this.object.position.x;
}

SOS.Routing.prototype.calcDirections = function(){
	this.isBottom = this.object.isObjectOnBottom(this.movement.objectCollisionY) ? 1 : -1;
	this.isRight = this.object.isObjectOnRight(this.movement.objectCollisionX) ? 1 : -1;
}

SOS.Routing.prototype.setAxisXOfPoint = function(){
	if (this.closerLeft < this.closerRight && !this.reverse)
		this.point.setPosition(this.movement.objectCollisionY.position.x - this.object.width);
	else this.point.setPosition(this.movement.objectCollisionY.position.x + this.movement.objectCollisionY.width + this.object.width);
}

SOS.Routing.prototype.setAxisYOfPoint = function(){
	if (this.closerTop < this.closerBottom && !this.reverse)
		this.point.setPosition(null, this.movement.objectCollisionX.position.y - this.object.height);
	else this.point.setPosition(null, this.movement.objectCollisionX.position.y + this.movement.objectCollisionX.height + this.object.height);
}

SOS.Routing.prototype.setAxisYOfPointWhenIsBorder = function(){
	if (this.counterY > 0 || SOS.view.Scene.isCollisionInBorderTop(this.point) || SOS.view.Scene.isCollisionInBorderBottom(this.point)){
		this.counterY += 5;
		this.point.setPosition(null, this.destination.position.y - (this.counterY*this.movement.directionY));
	}
}

SOS.Routing.prototype.setAxisXOfPointWhenIsBorder = function(){
	if (this.counterX > 0 || SOS.view.Scene.isCollisionInBorderLeft(this.point) || SOS.view.Scene.isCollisionInBorderRight(this.point)){
		this.counterX += 5;
		this.point.setPosition(this.destination.position.x - (this.counterX*this.movement.directionX));
	}
}

SOS.Routing.prototype.isSuccessfulEndOfRoute = function(object){
	let pos = this.object.calcAndGetDistanceBetween(object);
	return pos.x == 0 && pos.y == 0;
}
