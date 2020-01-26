SOSEngine.Timer = function(){
	var timers = [];

	this.clear = function(){
		for (var i in timers){
			window.clearTimeout(timers[i]);
			timers.shift();
		}
	}

	this.set = function(fn, delay){
		this.clear();
		timers.push(window.setTimeout(fn, delay));
	}
}
