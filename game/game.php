<?php

	require_once '../src/include.php';

	session_start();

	if (!SOS\Account::get()->isLogged() || empty($_SESSION['server-id']) || empty($_SESSION['general-id']))
	{
		header('location: '.SOS\Config::get()->path('main'));
		exit;
	}

	$general = new SOS\General;
	$general->setId($_SESSION['general-id']);
	$general->respawn();

	$map = new SOS\Map;
	$map->setId($general->getPositionManipulator()->selectThis(['id_map']));
	$mapData = $map->selectThis();
	$tracks = $map->getBackgroundMusic();

	$window = new STV\Window(new STV\Sheets\InGame);
	$window->setTitle('SOSGAME');

	foreach ($tracks as $track)
		$window->content()->appendTo('config', '<div class="track" data-src="'.$track.'"></div>');

	$window->content()->prependTo('config', '<div id="startData" data-id_map="'.+$mapData['id'].'" data-mapname="'.$mapData['name'].'" data-pvpable="'.$mapData['pvp_able'].'" data-generalid="'.$_SESSION['general-id'].'" data-playerid="'.SOS\Account::get()->getUserId().'"></div>');
	$window->content()->append($map->generateMap());

	$scripts = <<<EOL
	<script>
		SOS.pingServer = new WebSocket("ws://play.sosgame.online:90/ping");
		SOS.managementServer = new WebSocket("ws://play.sosgame.online:808{$_SESSION['server-id']}/management");
		SOS.battleSynchro = new WebSocket("ws://play.sosgame.online:900{$_SESSION['server-id']}/battle");
		SOS.synchro = new WebSocket("ws://play.sosgame.online:700{$_SESSION['server-id']}/synchro");
	</script>
EOL;

	$window->content()->appendTo('socket', $scripts.'<script src="js/SOS/sockets/ping.js"></script><script src="js/SOS/sockets/battle.js"></script><script src="js/SOS/sockets/management.js"></script><script src="js/SOS/sockets/synchro.js"></script>');

?>
