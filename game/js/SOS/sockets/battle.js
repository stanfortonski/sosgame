SOS.battleSynchro.onopen = function(){}
SOS.battleSynchro.onerror = function(){}
SOS.battleSynchro.onclose = function(){
	if (SOS.canClose)
		document.location.href = 'index';
}

SOS.battleSynchro.onmessage = function(msg){}

SOS.battleSynchro.query = SOS.battleSynchro.extQuery;
