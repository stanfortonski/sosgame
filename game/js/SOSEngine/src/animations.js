(function(){
	function AnimationList(){
		var anims = {};
			
		this.add = function(name, callback){
			if (typeof callback === 'function' && callback.length === 3)
				anims[name] = callback;
		}
			
		this.animate = function(objOrGrp, name, duration, callback){
			if (typeof anims[name] === 'function')
				anims[name](objOrGrp, duration || 400, callback || function(){});
		}
	}
	SOSEngine.groupAnimations = new AnimationList;
	SOSEngine.objectAnimations = new AnimationList;
})();