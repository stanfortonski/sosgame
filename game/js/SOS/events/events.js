SOS.events = {
	objectClick: new SOSEngine.Object(5, 5, 1, 1),
	cameraFollowing: true,
	maxCameraDistance: 50
};

SOS.events.init = function(){
	this.objectClick.obj.hide().addClass('icon icon-red-x');
	this.toFollow
	SOS.view.Scene.add(this.objectClick);

	if (!SOS.isMobile)
		SOS.interfaces.Title.getCursorPosition = SOS.events.getCursorPositionEventFunction($('#followed'), function(pos){$('#followed').css(pos);});

	this.onMovement();
	this.onTouchCamera();
	this.initInterface();

	$(document).on('visibilitychange', function(){
		if (!document.hidden){
			SOS.events.adjustInterfaceSize();
			if (SOS.view.global.is(':visible') && SOS.Config.timeToRespawn === undefined){
				SOS.synchro.query('players').query('items').query('mobs');
				SOS.ContextMenu.obj.hide();
			}
		}
	});
}

SOS.events.getCursorPositionEventFunction = function(object, callback, sides){
	sides = sides || {left: 22, collisionLeft: 44, top: 0, collisionTop: 44};
	if (sides.left === undefined) sides.left = 22;
	if (sides.top === undefined) sides.top = 0;
	if (sides.collisionLeft === undefined) sides.collisionLeft = 22;
	if (sides.collisionTop === undefined) sides.collisionTop = 22;

	return function(e){
		let pos = {left: e.pageX + sides.left, top: e.pageY + sides.top}, body = $('body');
		if (e.pageX + object.width() + sides.collisionLeft > body.width())
			pos.left = e.pageX - object.width() - sides.collisionLeft;
		if (e.pageY + object.height() + sides.collisionTop > body.height())
			pos.top = body.height() - object.height() - sides.collisionTop;
		callback(pos);
	}
}

SOS.events.targetOnScene = function(e){
	return{
		x: parseInt((e.pageX - SOS.view.Scene.obj.offset().left)/SOSEngine.scale)+1,
		y: parseInt((e.pageY - SOS.view.Scene.obj.offset().top)/SOSEngine.scale)+1
	}
}
