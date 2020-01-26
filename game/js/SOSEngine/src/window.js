SOSEngine.Window = function(main){
	this.main = main;
	this.obj = this.main.global.find('.window');
	SOSEngine.StaticObject.call(this, this.obj);

	$(window).on('resize.SOSEngine', this.resize.bind(this));
}
SOSEngine.Window.prototype = Object.create(SOSEngine.StaticObject.prototype);

SOSEngine.Window.prototype.resize = function(){
	this.init();
	this.main.Camera.init();
}
