SOSEngine.Test.tests.push(function(){
	QUnit.module("Group Test");
	
	QUnit.test("Adding", function(assert){
		let group = new SOSEngine.Group;
		
		group.add(new SOSEngine.Object);
		group.add(new SOSEngine.Group);
		
		assert.equal(group.objects.length, 2, 'Size of array is correct');
		assert.equal(group.obj.children().length, 2, 'Count of children is correct');
		
		assert.ok(group.objects[0] instanceof SOSEngine.Object, 'This is instance of Object');
		assert.ok(group.objects[1] instanceof SOSEngine.Group, 'This is instance of Static Group');
	});
	
	QUnit.test("Removing", function(assert){
		let group = new SOSEngine.Group;
		
		group.add(new SOSEngine.Object);
		group.remove(0);
		
		assert.equal(group.objects.length, 0, 'Array is empty');
		assert.equal(group.obj.children().length, 0, 'There are no children');
	});
	
	QUnit.test("Layer", function(assert){
		let group = new SOSEngine.Group(23);
		
		assert.equal(group.layer, 23, 'Layer is correct');
		assert.equal(group.obj.css('z-index'), 23, 'Z-index is correct');
		
		group.setOnLayer(5);
		assert.equal(group.layer, 5, 'Layer is correct');
		assert.equal(group.obj.css('z-index'), 5, 'Z-index is correct');
	});
});