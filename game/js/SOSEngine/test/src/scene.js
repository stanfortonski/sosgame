SOSEngine.Test.tests.push(function(){
	QUnit.module("Scene Test");

	QUnit.test("Loading static objects", function(assert){
		var objects = SOSEngine.Test.main.Scene.static.objects;
		assert.equal(objects.length, 2, "Count of objects is correct");
		assert.ok(objects[0] instanceof SOSEngine.StaticObject, "First object is instance of StaticObject");
		assert.ok(objects[1] instanceof SOSEngine.StaticGroup, "Last object is instance of StaticGroup");
		assert.equal(objects[1].objects.length, 1, "Count of objects in group is correct");
		assert.ok(objects[1].objects[0] instanceof SOSEngine.StaticObject, "Object is instance of StaticObject");
	});
});
