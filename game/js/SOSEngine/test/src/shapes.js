SOSEngine.Test.tests.push(function(){
	QUnit.module("Shapes Test");
	
	QUnit.test("Cross", function(assert){
		let cross = new SOSEngine.shapes.Cross(1, 2, 3, 4, 5);
		assert.equal(cross.width, 1, "Width is correct");
		assert.equal(cross.height, 2, "Height is correct");
		assert.equal(cross.position.x, 3, "Position x is correct");
		assert.equal(cross.position.y, 4, "Position y is correct");
		assert.equal(cross.fat, 5, "Fat is correct");	
	});
	
	QUnit.test("Arc", function(assert){
		let arc = new SOSEngine.shapes.Arc(1, 2, 3, 4, 5);
		assert.equal(arc.radius, 1, "Radius is correct");
		assert.equal(arc.startAngle, 2, "Start angle is correct");
		assert.equal(arc.endAngle, 3, "End angle is correct");
		assert.equal(arc.position.x, 4, "Position x is correct");
		assert.equal(arc.position.y, 5, "Position y is correct");
	});
	
	QUnit.test("Circle", function(assert){
		let circle = new SOSEngine.shapes.Circle(1, 2, 3);
		assert.equal(circle.radius, 1, "Radius is correct");
		assert.equal(circle.startAngle, 0, "Start angle is correct");
		assert.equal(circle.endAngle, 360, "End angle is correct");
		assert.equal(circle.position.x, 2, "Position x is correct");
		assert.equal(circle.position.y, 3, "Position y is correct");
	});
	
	QUnit.test("Line", function(assert){
		let line = new SOSEngine.shapes.Line(1, 2, 3, 4, 5);
		assert.equal(line.from.x, 1, "From position x is correct");
		assert.equal(line.from.y, 2, "From position y is correct");
		assert.equal(line.to.x, 3, "To position x is correct");
		assert.equal(line.to.y, 4, "To position y is correct");
		assert.equal(line.fat, 5, "Fat is correct");
	});
});