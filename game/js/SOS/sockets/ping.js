SOS.pingServer.onclose = function(){}
SOS.pingServer.onerror = function(){}

SOS.pingServer.onopen = function(){
	let timer = new SOSEngine.Timer;
	(function reloadPing(){
		SOS.pingServer.send('');
		timer.set(reloadPing, 2000);
	})();
}

SOS.pingServer.onmessage = function(msg){
	if (SOS.MainInterface !== undefined)
		$('#ping').html(msg.data);
}
