SOS.events.onDoors = function(){
	function teleport(jqueryObj){
		let data = jqueryObj.data(),
		door = new SOSEngine.Object(5, 5, data.x, data.y);

		SOS.me.move.to(door, function(){
			SOS.view.Camera.lookAtAnimate(SOS.me.view);
		}.bind(this), function(){
			SOS.view.Camera.lookAtAnimate(SOS.me.view);
			SOS.synchro.query('change-map', {position: data.position});
		});
	}

	$('.door').on('click.movement', function(e){
		let self = $(this);
		SOS.ContextMenu.setList(e, self, [{
			name: 'Przejdź',
			click: function(e){
				teleport(self);
			}
		}]);
		e.stopPropagation();
	});

	$('.door').on('dblclick.movement', function(e){
		teleport($(this));
	});
}
