SOSEngine.Test.tests.push(function(){
	QUnit.module("Object Manipulator Test");
	
	var object = new SOSEngine.ObjectManipulator;
	object.obj = $('<div></div>');
	
	QUnit.test("Layer", function(assert){
		assert.equal(object.layer, undefined, 'Layer is undefined');
		
		object.setOnLayer(5);
		assert.equal(object.layer, 5, 'Layer is correct');
		assert.equal(object.obj.css('z-index'), 5, 'Z-index is correct');
	});
	
	QUnit.test("Setting size", function(assert){
		let good = {width: 100, height: 32},
			bad = {width: 100.321, height: 32.321};

		object.setSize(undefined, "string");
		assert.strictEqual(object.width, null, "Width is null");
		assert.strictEqual(object.height, null, "Height is null");
			
		object.setSize(bad.width, bad.height);
		assert.strictEqual(object.width, good.width, "Width is correct and improved");
		assert.strictEqual(object.height, good.height, "Height is correct and improved");
		
		object.setSize(good.width, good.height);
		assert.strictEqual(object.width, good.width, "Width is correct");
		assert.strictEqual(object.height, good.height, "Height is correct");
	});
	
	QUnit.test("Setting position", function(assert){
		let good = {x: 100, y: 32},
			bad = {x: 100.321, y: 32.321};

		object.setPosition(null, good.y);
		assert.strictEqual(object.position.x, null, "Position x is null");
		assert.strictEqual(object.position.y, good.y, "Position y is correct");
		
		object.setPosition(good.x);
		assert.strictEqual(object.position.x, good.x, "Position x is correct");
		assert.strictEqual(object.position.y, good.y, "Position y is correct");
	
		object.setPosition(bad.x, bad.y);
		assert.strictEqual(object.position.x, good.x, "Position x is correct and improved");
		assert.strictEqual(object.position.y, good.y, "Position y is correct and improved");
	});
	
	QUnit.test("Texturing", function(assert){
		let value = "red";
		
		object.setTexture(value);
		assert.equal(object.obj.css('background'), value, "Texture is correct");
		assert.equal(object.getTexture(), value, "Texture is correct");
	});
	
	QUnit.test("Rounding", function(assert){
		object.round(32);
		assert.equal(object.obj.css('border-radius'), (SOSEngine.scale*object.width/2)+'px', "Round is correct");
	});
	
	QUnit.test("Rotating", function(assert){
		let value = 32;
		
		object.rotate(value);
		assert.strictEqual(object.rotation, value, "Rotation is correct");
	});
});