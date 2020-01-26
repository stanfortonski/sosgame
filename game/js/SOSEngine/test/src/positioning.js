SOSEngine.Test.tests.push(function(){
	QUnit.module("Positioning");
	
	var scene = SOSEngine.Test.main.Scene;
	QUnit.test("Objects positions", function(assert){
		let object = new SOSEngine.Object(1, 1, 1, 1);
	
		object.setPosition(-100, -100);
		assert.strictEqual(object.position.x, 1, "Position X is correct");
		assert.strictEqual(object.position.x, 1, "Position Y is correct");
		
		object.setPosition(scene.width+100, scene.height+100);
		assert.strictEqual(object.position.x, scene.width+100, "Position X is correct");
		assert.strictEqual(object.position.x, scene.height+100, "Position Y is correct");
		
		scene.add(object);
		object.setPosition(scene.width+100, scene.height+100);
		assert.strictEqual(object.position.x, scene.width - object.width+1, "Position X is correct");
		assert.strictEqual(object.position.x, scene.height - object.height+1, "Position Y is correct");
		
		object.setPosition(0, 0);
		assert.strictEqual(object.position.x,  1, "Position X is correct");
		assert.strictEqual(object.position.x, 1, "Position Y is correct");
	});
	
	QUnit.test("Scene positions", function(assert){
		scene.setPosition(-100, -100);
		assert.strictEqual(scene.position.x, -100, "Position X is correct");
		assert.strictEqual(scene.position.x, -100, "Position Y is correct");
		
		scene.setPosition(0, 0);
		assert.strictEqual(scene.position.x,  1, "Position X is correct");
		assert.strictEqual(scene.position.y, 1, "Position Y is correct");
	});
});