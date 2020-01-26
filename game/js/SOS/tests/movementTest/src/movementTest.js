SOS.Test = {tests: [], delay: 800};
SOS.Test.destination = new SOSEngine.Object(5, 5);
SOS.Test.object = new SOSEngine.Object(10, 18, 35, 1);
SOS.Test.object.setTexture('blue');
SOS.Test.object.move = new SOS.Travel(SOS.Test.object);
SOS.view.Scene.add(SOS.Test.object);
SOS.Routing = new SOS.Routing;
SOS.Routing.destination.setTexture('red');
SOS.Routing.object.setTexture('purple');
SOS.Routing.point.setTexture('yellow');
SOS.Routing.movement.fastMethod = true;
SOS.MovementExtended.delay = 5;

SOS.Test.init = function(){
	$.each(this.tests, function(i, func){
		func();
	});
}

$(window).ready(function(){
	SOS.Test.init();
});

SOS.Test.tests.push(function(){
	QUnit.module('Routing Test - Simple Object');

	QUnit.test('From Top To Bottom', function(assert){
		SOS.Test.object.setPosition(35, 1);
		SOS.Test.destination.setPosition(40, 55);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Bottom To Top', function(assert){
		SOS.Test.object.setPosition(40, 55);
		SOS.Test.destination.setPosition(42, 5);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Right To Left', function(assert){
		SOS.Test.object.setPosition(4, 25);
		SOS.Test.destination.setPosition(70, 30);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Left To Right', function(assert){
		SOS.Test.object.setPosition(65, 25);
		SOS.Test.destination.setPosition(8, 32);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});
});

SOS.Test.tests.push(function(){
	QUnit.module('Routing Test - Object Near Border');

	QUnit.test('From Top To Bottom - Very Near Border', function(assert){
		SOS.Test.object.setPosition(3, 50);
		SOS.Test.destination.setPosition(1, 78);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Top To Bottom', function(assert){
		SOS.Test.object.setPosition(3, 50);
		SOS.Test.destination.setPosition(20, 78);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Bottom To Top - Very Near Border', function(assert){
		SOS.Test.object.setPosition(20, 78);
		SOS.Test.destination.setPosition(3, 50);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Bottom To Top', function(assert){
		SOS.Test.object.setPosition(20, 78);
		SOS.Test.destination.setPosition(20, 50);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});
});

SOS.Test.tests.push(function(){
	QUnit.module('Routing Test - Simple Object Semi');

	QUnit.test('From Top To Left', function(assert){
		SOS.Test.object.setPosition(30, 4);
		SOS.Test.destination.setPosition(25, 35);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Top To Right', function(assert){
		SOS.Test.object.setPosition(30, 4);
		SOS.Test.destination.setPosition(65, 30);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Right To Top', function(assert){
		SOS.Test.object.setPosition(65, 30);
		SOS.Test.destination.setPosition(30, 15);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Left To Top', function(assert){
		SOS.Test.object.setPosition(18, 35);
		SOS.Test.destination.setPosition(38, 15);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});
});

SOS.Test.tests.push(function(){
	QUnit.module('Routing Test - Medium Object');

	QUnit.test('From Top Left Corner To Left Bottom Corner', function(assert){
		SOS.Test.object.setPosition(130, 30);
		SOS.Test.destination.setPosition(125, 70);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Top Left Corner To Right Top Corner', function(assert){
		SOS.Test.object.setPosition(130, 30);
		SOS.Test.destination.setPosition(165, 45);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Right Left Corner To Right Bottom Corner', function(assert){
		SOS.Test.object.setPosition(130, 30);
		SOS.Test.destination.setPosition(150, 70);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Bottom Left Corner To Left Top Corner', function(assert){
		SOS.Test.object.setPosition(122, 70);
		SOS.Test.destination.setPosition(130, 30);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Bottom Left Corner To Right Top Corner', function(assert){
		SOS.Test.object.setPosition(122, 70);
		SOS.Test.destination.setPosition(165, 45);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Bottom Left Corner To Bottom Left Corner', function(assert){
		SOS.Test.object.setPosition(122, 70);
		SOS.Test.destination.setPosition(152, 68);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Bottom Right Corner To Bottom Left Corner', function(assert){
		SOS.Test.object.setPosition(152, 68);
		SOS.Test.destination.setPosition(122, 70);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Bottom Right Corner To Top Left Corner', function(assert){
		SOS.Test.object.setPosition(152, 68);
		SOS.Test.destination.setPosition(128, 42);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Bottom Right Corner To Right Top Corner', function(assert){
		SOS.Test.object.setPosition(152, 68);
		SOS.Test.destination.setPosition(160, 35);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Top Right Corner To Right Bottom Corner', function(assert){
		SOS.Test.object.setPosition(165, 32);
		SOS.Test.destination.setPosition(152, 68);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Top Right Corner To Left Bottom Corner', function(assert){
		SOS.Test.object.setPosition(165, 32);
		SOS.Test.destination.setPosition(125, 68);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});

	QUnit.test('From Top Right Corner To Left Bottom Corner', function(assert){
		SOS.Test.object.setPosition(165, 32);
		SOS.Test.destination.setPosition(130, 35);
		SOS.Routing.init(SOS.Test.object, SOS.Test.destination);
		SOS.Routing.makeTableRouting();

		let done = assert.async();
		setTimeout(function(){
			assert.equal(SOS.Routing.isFound, true, 'Correct routing');
			done();
		}, SOS.Test.delay);
	});
});
