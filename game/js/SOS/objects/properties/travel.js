SOS.Travel = function(object, anim){
	SOS.MovementExtended.call(this, object, anim);
	this.travelTimer = new SOSEngine.Timer;
	this.toFollow = new SOSEngine.Object(5, 5);
	this.toFollow.obj.click(function(e){e.stopPropagation();});
	SOS.view.Scene.add(this.toFollow);
}
SOS.Travel.prototype = Object.create(SOS.MovementExtended.prototype);

SOS.Travel.prototype.to = function(to, callback, endCallback){
	var i = 0, limit = 80;
	if (SOS.Routing.movement.fastMethod) limit = 20;

	SOS.Routing.init(this.object, to);
	SOS.Routing.makeTableRouting();
	listener.call(this);

	function listener(){
		++i;
		if (SOS.Routing.isFound == true && SOS.Routing.movement.destination == null){
			SOS.me.move.goTravel(SOS.Routing.ways, callback, endCallback);
		}
		else if (i-1 == limit){
			endCallback();
			SOS.me.move.travelTimer.set(listener.bind(this), 50);
		}
		else if (i <= limit){
			SOS.me.move.travelTimer.set(listener.bind(this), 50);
		}
	}
}

SOS.Travel.prototype.goTravel = function(ways, callback, endCallback){
	this.travelTimer.clear();
	this.timer.clear();
	this.stopTravel();
	this.strict = true;

	journey.call(this);
	function journey(){
		if (this.actualWay <= ways.length){
			if (this.destination == null){
				if (this.actualWay == ways.length-2 || this.actualWay == ways.length-1)
					this.strict = false;
				else this.strict = true;

				this.toFollow.setPosition(ways[this.actualWay].x, ways[this.actualWay].y);
				this.goTo(this.toFollow, function(){
					if (typeof callback === 'function')
						callback();
					++this.actualWay;

					if (this.actualWay != ways.length)
						this.travelTimer.set(journey.bind(this), 50);
					else this.stopTravel(endCallback);
				}.bind(this));
			}
		}
	}
}

SOS.Travel.prototype.stopTravel = function(callback){
	this.destination = null;
	this.actualWay = 0;

	if (typeof callback === 'function')
		callback();
}
