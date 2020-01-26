SOSEngine.Test.tests.push(function(){
	QUnit.module("Object Test");

	QUnit.test("Constructor", function(assert){
		var prop = 10, object = new SOSEngine.Object(prop+1, prop+2, prop+3, prop+4);

		assert.strictEqual(object.width, prop+1, "Width is correct");
		assert.strictEqual(object.height, prop+2, "Height is correct");
		assert.strictEqual(object.position.x, prop+3, "Position x is correct");
		assert.strictEqual(object.position.y, prop+4, "Position y correct");
	});

	QUnit.test("Distances", function(assert){
		let object = new SOSEngine.Object(2, 2, 2, 2),
			anotherObject = new SOSEngine.Object(2, 2, 6, 6),
			distance = object.calcAndGetDistanceBetween(anotherObject);

		assert.strictEqual(distance.x, 2, "Distance x is correct");
		assert.strictEqual(distance.y, 2, "Distance y is correct");

		anotherObject.setPosition(10, 10);
		distance = object.calcAndGetDistanceBetween(anotherObject);
		assert.strictEqual(distance.x, 6, "Distance x is correct");
		assert.strictEqual(distance.y, 6, "Distance y is correct");

		anotherObject.setPosition(7, 7);
		object.setPosition(10, 10);
		distance = object.calcAndGetDistanceBetween(anotherObject);
		assert.strictEqual(distance.x, -1, "Distance x is correct");
		assert.strictEqual(distance.y, -1, "Distance y is correct");

		anotherObject.setPosition(10, 10);
		distance = object.calcAndGetDistanceBetween(anotherObject);
		assert.strictEqual(distance.x, 0, "Distance x is zero");
		assert.strictEqual(distance.y, 0, "Distance y is zero");
	});

	QUnit.test("Collisions", function(assert){
		let object = new SOSEngine.Object(2, 2, 10, 10),
			anotherObject = new SOSEngine.Object(2, 2, 8, 10);

		assert.ok(object.isCollisionX(anotherObject), "Collision x detected");
		assert.ok(!object.isCollisionY(anotherObject), "Collision y is not detected");

		anotherObject.setPosition(8, 8);
		assert.ok(!object.isCollision(anotherObject), "Collision is not detected");

		object.setPosition(8, 6);
		assert.ok(object.isCollisionY(anotherObject), "Collision y is detected");
		assert.ok(!object.isCollisionX(anotherObject), "Collision x is not detected");

		object.setPosition(10, 8);
		assert.ok(object.isCollision(anotherObject), "Collision left is detected");

		object.setPosition(6, 8);
		assert.ok(object.isCollision(anotherObject), "Collision right is detected");

		object.setPosition(8, 10);
		assert.ok(object.isCollision(anotherObject), "Collision bottom is detected");

		object.setPosition(8, 6);
		assert.ok(object.isCollision(anotherObject), "Collision top is detected");

		object.setPosition(8, 8);
		assert.ok(!object.isCollision(anotherObject), "Collision is not detected");
	});
});
