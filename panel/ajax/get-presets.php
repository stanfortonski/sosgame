<?php

	require_once '../../src/include.php';
	
	session_start();

	if (!SOS\Account::get()->isLogged() || empty($_GET['race']) || !is_numeric($_GET['race']))
		exit;
	
	echo SOS\PlayerManager::get()->makeChooseGeneralPreset($_GET['race']);
?>