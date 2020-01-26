SOSEngine.Test.tests.push(function(){
	QUnit.module("Static Object Test");

	QUnit.test("Constructor", function(assert){
		let object = new SOSEngine.StaticObject($('#TEST'));
		assert.strictEqual(object.width, 5, "Width is correct");
		assert.strictEqual(object.height, 5, "Height is correct");
		assert.strictEqual(object.position.x, 25, "Position x is correct");
		assert.strictEqual(object.position.y, 12, "Position y is correct");
		assert.strictEqual(object.rotation, 0, "Rotation is correct");
	});
});
