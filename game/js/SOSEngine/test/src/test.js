SOSEngine.Test = {main: SOSEngine.make(), tests: []};

SOSEngine.Test.init = function(){
	$.each(this.tests, function(i, func){
		func();
	});
}

$(window).ready(function(){
	SOSEngine.Test.init();
});