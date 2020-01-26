SOS.Loading = new SOS.Loading;
SOS.Loading.updateLoadingBar('Łączenie z serwerem');

SOS.beforeOnload = function(){
	SOS.Loading.updateLoadingBar('Przygotowywanie obiektów');
	SOS.ContextMenu = new SOS.interfaces.ContextMenu;
	SOS.playersList = new SOS.interfaces.ListOfPlayers;
	SOS.friendStack = new SOS.interfaces.FriendStack;
	SOS.enemiesList = new SOS.interfaces.ListOfEnemies;
	SOS.players = new SOS.Group;
	SOS.mobs = new SOS.Group;
	SOS.items = new SOS.Group;
	SOS.Chat = new SOS.Chat;
	SOS.Notification = new SOS.interfaces.NotificationBox;
	SOS.teamNet = new SOS.CharactersNet;
	SOS.enemiesNet = new SOS.CharactersNet;
	SOS.equipment = new SOS.interfaces.Equipment;
	SOS.MainInterface = new SOS.interfaces.Main;
	SOS.BattleInterface = new SOS.interfaces.Battle;
	SOS.Routing = new SOS.Routing;

	SOS.view.Scene.add(SOS.players.view);
	SOS.view.Scene.add(SOS.mobs.view);
	SOS.view.Scene.add(SOS.items.view);

	let tracks = [];
	$('.track').each(function(){
		tracks.push($(this).data().src);
	});
	SOS.backgroundMusic = SOS.Audio('audio/music', tracks);
	SOS.audioEffects = SOS.Audio('audio/effects');
	SOS.audioEffects.current = null;
	SOS.battleMusic = SOS.Audio('audio/music/battle', [
		'Boss Theme.ogg',
		'Dark Descent.mp3',
		'Heroic Demise (New).mp3',
		'TheLoomingBattle.ogg'
	]);
}

SOS.onload = function(){
	SOS.teamNet.setFocus();
	SOS.managementServer.query('options-get').query('team');

	if (SOS.Config.timeToRespawn === undefined)
		SOS.synchro.query('enemies').query('friends').query('players').query('items').query('mobs');

	if (!SOS.isMobile)
			this.setEarthQuake();

	SOS.MainInterface.initMiniMap();
	SOS.MainInterface.loadPositionsAndSizes();

	SOS.Loading.end(function(){
		SOS.events.init();
		SOS.Config.init();

		if (SOS.timeToRespawn !== undefined)
			SOS.events.onDie({time: SOS.timeToRespawn});
		else{
			if (!SOS.Config.soundMute)
				SOS.backgroundMusic.play();
		}
	});
	item = new SOS.Item({id: 1, id_rank: 3, id_pos: 1, path: 'imgs/items/sword.png', stack: 2, amount: 2, posX: 0, posY: 0});
	SOS.equipment.addItem(1, item);
}
