SOS.events.onMovement = function(){
	$(document).on('keydown.movement', this.movementKeyDown.bind(this));
	$(document).on('keyup.movement', this.movementKeyUp.bind(this));
	$('#SOSEngine > .window > .scene, .introduce, #worldInfo, .rightMenu').on('click.movement', this.clickOnScene.bind(this));
	$('#SOSEngine > .window > .scene .object').on('click.movement', function(e){e.stopPropagation();});
	SOS.Routing.destination.obj.on('click.movement', SOS.events.clickOnScene.bind(this));
	SOS.Routing.object.obj.on('click.movement', SOS.events.clickOnScene.bind(this));
	SOS.Routing.point.obj.on('click.movement', SOS.events.clickOnScene.bind(this));
	SOS.events.onDoors();
	$('.rightMenu .button').on('click', function(e){e.stopPropagation();});
}

SOS.events.offMovement = function(){
	$(document).off('.movement');
	$('#SOSEngine > .window > .scene, #SOSEngine > .window > .scene .object, .door, .introduce').off('.movement');
	SOS.Routing.destination.obj.off('.movement');
	SOS.Routing.object.obj.off('.movement');
	SOS.Routing.point.obj.off('.movement');
	SOS.me.move.stop();
	this.objectClick.obj.fadeOut();
	SOS.view.Camera.lookAt(SOS.me.view);
}

SOS.events.movementKeyDown = function(e){
	SOS.me.move.step = 2;
	this.delay = 0;

	switch (e.keyCode || e.which){
		case 'D'.charCodeAt(0):
		case 'd'.charCodeAt(0):
		case 39:
			SOS.me.move.right();
		break;

		case 'A'.charCodeAt(0):
		case 'a'.charCodeAt(0):
		case 37:
			SOS.me.move.left();
		break;

		case 'W'.charCodeAt(0):
		case 'w'.charCodeAt(0):
		case 38:
			SOS.me.move.top();
		break;

		case 'S'.charCodeAt(0):
		case 's'.charCodeAt(0):
		case 40:
			SOS.me.move.bottom();
		break;
	}

	SOS.me.move.stopTravel();
	SOS.me.move.travelTimer.clear();
	SOS.me.move.timer.clear();
	this.objectClick.obj.fadeOut();

	if (this.cameraFollowing)
		SOS.view.Camera.lookAtAnimate(SOS.me.view);
}

SOS.events.movementKeyUp = function(e){
	SOS.me.move.stand();
}

SOS.events.clickOnScene = function(e){
	let pos = this.targetOnScene(e);
	this.objectClick.setPosition(pos.x, pos.y);
	this.objectClick.obj.show();

	SOS.me.move.to(this.objectClick, function(){
		SOS.view.Camera.lookAtAnimate(SOS.me.view);
	}.bind(this), function(){
		this.objectClick.obj.fadeOut(200);
	}.bind(this));

	e.stopPropagation();
}
