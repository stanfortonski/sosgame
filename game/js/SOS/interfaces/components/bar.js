SOS.interfaces.Bar = function(){
	this.main = $('<div class="bar bar-position"><div class="label"></div></div>').progressbar({max: 100, value: 0,
		classes: {"ui-progressbar": "","ui-progressbar-complete": "","ui-progressbar-value": ""}
	});
	this.label = this.main.find('.label');
}

SOS.interfaces.Bar.prototype.update = function(maxValue, actualValue, newLabel){
	this.main.progressbar({max: maxValue, value: actualValue});
	if (newLabel){
		let procent = Math.round((actualValue*100)/maxValue);
		this.label.text(procent+'% ('+actualValue+'/'+maxValue+')');
	}
}
