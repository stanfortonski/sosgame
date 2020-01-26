var SOS = {
	tests: [],
	items: [],
	interfaces: {},
	players: {add: function(){}},
	mobs: {add: function(){}},
	items: {add: function(){}},
	events: {
		onMovement: function(){},
		offMovement: function(){}
	},
	Config: {
		chatMute: false,
		chatMutePm: false,
	},
	isMobile: false,
	PATH: 'D:/xampp/htdocs/projects/sosgame/game/'
};

SOS.init = function(){
	SOS.playersList = new SOS.interfaces.ListOfPlayers;
	SOS.friendStack = new SOS.interfaces.FriendStack;
	SOS.enemiesList = new SOS.interfaces.ListOfEnemies;
	SOS.Chat = new SOS.Chat;
	$.each(SOS.tests, function(i, func){
		func();
	});
}

$(window).ready(function(){
	SOS.init();
});
