SOS.Group = function(layer){
	this.storage = [];
	this.view = new SOSEngine.Group(layer);
}

SOS.Group.prototype.add = function(object){
	this.view.add(object.view);
	this.storage.push(object);
}

SOS.Group.prototype.remove = function(index){
	this.view.remove(index);
	this.storage.splice(index, 1);
}

SOS.Group.prototype.clear = function(){
	this.view.clear();
	this.storage.splice(0, this.storage.length);
}