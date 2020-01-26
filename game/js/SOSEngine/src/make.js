SOSEngine.make = function(jqueryObj){
	var main = new SOSEngine(jqueryObj);
	main.Window = new SOSEngine.Window(main);
	main.Scene = new SOSEngine.Scene(main);
	main.Camera = new SOSEngine.Camera(main);
	return main;
}
