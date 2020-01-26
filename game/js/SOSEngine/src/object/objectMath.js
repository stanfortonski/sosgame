SOSEngine.ObjectManipulator.prototype.isCollisionLeft = function(object){
	return this.isCollisionX(object) && this.isObjectOnLeft(object);
}

SOSEngine.ObjectManipulator.prototype.isCollisionRight = function(object){
	return this.isCollisionX(object) && this.isObjectOnRight(object);
}

SOSEngine.ObjectManipulator.prototype.isCollisionTop = function(object){
	return this.isCollisionY(object) && this.isObjectOnTop(object);
}

SOSEngine.ObjectManipulator.prototype.isCollisionBottom = function(object){
	return this.isCollisionY(object) && this.isObjectOnBottom(object);
}

SOSEngine.ObjectManipulator.prototype.isCollision = function(object){
	return this.isCollisionX(object) || this.isCollisionY(object);
}

SOSEngine.ObjectManipulator.prototype.isCollisionY = function(object){
	if (object.height < 1) return false;

	let lastPos = this.getLastPosition(), objLastPos = object.getLastPosition();
	++lastPos.x, ++lastPos.y, ++objLastPos.x, ++objLastPos.y;

	if (objLastPos.y == this.position.y || object.position.y == lastPos.y){
		if (lastPos.x > object.position.x && this.position.x < objLastPos.x)
			return true;
	}
	return false;
}

SOSEngine.ObjectManipulator.prototype.isCollisionX = function(object){
	if (object.width < 1) return false;

	let lastPos = this.getLastPosition(), objLastPos = object.getLastPosition();
	++lastPos.x, ++lastPos.y, ++objLastPos.x, ++objLastPos.y;

	if (objLastPos.x == this.position.x || object.position.x == lastPos.x){
		if (lastPos.y > object.position.y && this.position.y < objLastPos.y)
			return true;
	}
	return false;
}

SOSEngine.ObjectManipulator.prototype.isObjectOnLeft = function(object){
	return object.position.x - this.position.x < 0;
}

SOSEngine.ObjectManipulator.prototype.isObjectOnRight = function(object){
	return this.position.x - object.position.x < 0;
}

SOSEngine.ObjectManipulator.prototype.isObjectOnTop = function(object){
	return object.position.y - this.position.y < 0;
}

SOSEngine.ObjectManipulator.prototype.isObjectOnBottom = function(object){
	return this.position.y - object.position.y < 0;
}

SOSEngine.ObjectManipulator.prototype.calcAndGetDistanceBetween = {};
(function(){
	function calcAndGetUsedFieldsOnBorder(start, end){
		let fields = [];

		for (let col = start.y; col <= end.y; ++col){
			fields.push({x: start.x, y: col});
			fields.push({x: end.x, y: col});
		}

		for (let row = start.x; row <= end.x; ++row){
			fields.push({x: row, y: start.y});
			fields.push({x: row, y: end.y});
		}
		return fields;
	}

	function sorting(a, b){
		return Math.abs(a) - Math.abs(b);
	}

	SOSEngine.ObjectManipulator.prototype.calcAndGetDistanceBetween = function(object){
		let distancesX = [], distancesY = [],
			fields = calcAndGetUsedFieldsOnBorder(this.getFirstPosition(), this.getLastPosition()),
			objFields = calcAndGetUsedFieldsOnBorder(object.getFirstPosition(), object.getLastPosition());

		for (let i = 0; i < fields.length; ++i){
			for (let j = 0; j < objFields.length; ++j){
				let temp = objFields[j].x - fields[i].x;
				if (temp > 0) --temp;
				else if (temp < 0) ++temp;
				distancesX.push(temp);

				temp = objFields[j].y - fields[i].y;
				if (temp > 0) --temp;
				else if (temp < 0) ++temp;
				distancesY.push(temp);
			}
		}
		distancesX.sort(sorting);
		distancesY.sort(sorting);

		return {x: distancesX[0], y: distancesY[0]};
	}
})();
