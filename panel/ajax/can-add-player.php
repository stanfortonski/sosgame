<?php

	require_once '../../src/include.php';

	session_start();

	if (!SOS\Account::get()->isLogged() || empty($_GET['name']) || empty($_GET['server']) || !is_numeric($_GET['server']))
		exit;

	$player = new SOS\Player;
	$player->setId(SOS\Account::get()->getUserId());
	if (!$player->canAddGeneral($_GET['server'], $_GET['name']))
		echo '{"error": "Nie można utworzyć postaci o tej nazwie."}';
	else echo '{}';

?>
