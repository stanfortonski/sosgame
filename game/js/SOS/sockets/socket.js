WebSocket.prototype.query = function(name, value){
	if (name === undefined) return;
	value = value || '';
	this.send(JSON.stringify({name: name, value: value}));
	return this;
}

WebSocket.prototype.extQuery = function(name, value){
	value = value || {};
	value.generalId = SOS.idGeneral;
	value.playerId = SOS.idPlayer;
	return WebSocket.prototype.query.call(this, name, value);
}
