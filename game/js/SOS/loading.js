SOS.isLoaded = function(){
	return SOS.interfaces.Loading === undefined;
}

SOS.Loading = function(){
	this.splash = $('#splash');
	this.bar = new SOS.interfaces.Bar;
	this.states = 2;
	this.actualState = 0;
	this.introduce = true;
	this.init();
}

SOS.Loading.prototype.init = function(){
	this.bar.main = this.splash.find('.bar').progressbar({max: 100, value: 0});
	this.bar.label = this.bar.main.find('.label');
}

SOS.Loading.prototype.updateLoadingBar = function(label){
	if (this.actualState < this.states){
		let maxVal = 100, nextState = ++this.actualState * (maxVal/this.states);
		this.bar.update(maxVal, nextState);
		setTimeout(function(){this.bar.label.text(label || 'Ładowanie');}.bind(this), 100);
	}
	else this.end();
}

SOS.Loading.prototype.end = function(callback){
 	callback = callback ? callback : function(){};

	this.splash.delay(700).fadeOut(500, function(){
		$('main').css({visibility: 'visible'}).fadeIn(500);

		if (this.introduce){
			let name = $('#startData').data().mapname.toUpperCase();
			$('<div class="centrum introduce unselectable" unselectable="on"></div>').hide().appendTo($('body')).text(name).fadeIn().delay(1500).fadeOut();
		}
		this.clear();
		callback();
	}.bind(this));
}

SOS.Loading.prototype.clear = function(){
	SOS.Loading = null;
	delete SOS.Loading;
}
