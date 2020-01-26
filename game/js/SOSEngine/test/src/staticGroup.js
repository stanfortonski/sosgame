SOSEngine.Test.tests.push(function(){
	QUnit.module("Static Group Test");
	
	QUnit.test("Constructor", function(assert){
		let group = new SOSEngine.StaticObject($('#GROUPTEST'));
		assert.strictEqual(group.layer, 50, "Layer is correct");
	});
});