SOSEngine.StaticImage = function(jqueryObj){
	SOSEngine.StaticObject.call(this, jqueryObj);
	
	let data = this.obj.data();
	this.setScale(data.scalex, data.scaley);
	
	if (data.src !== undefined)
		this.setSource(data.src);
}
SOSEngine.StaticImage.prototype = Object.create(SOSEngine.StaticObject.prototype);
SOSEngine.StaticImage.prototype.setSource = SOSEngine.Image.prototype.setSource;
SOSEngine.StaticImage.prototype.setScale = SOSEngine.Image.prototype.setScale;
