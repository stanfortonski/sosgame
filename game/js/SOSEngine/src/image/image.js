SOSEngine.Image = function(src, width, height, scaleX, scaleY, x, y){
	this.obj = $('<div class="image"></div>');
	SOSEngine.ObjectManipulator.call(this);
	
	this.setSource(src);
	this.setScale(scaleX, scaleY);
	this.setSize(width, height);
	this.setPosition(x, y);
}
SOSEngine.Image.prototype = Object.create(SOSEngine.ObjectManipulator.prototype);

SOSEngine.Image.prototype.setSource = function(src){
	this.obj.css('background-image', 'url('+src+')');
}

SOSEngine.Image.prototype.setScale = function(width, height){
	if (width === undefined || width === null)
		width = 'auto';
	
	if (height === undefined || height === null)
		height = 'auto';

	this.obj.css('background-size', width+' '+height);
}
