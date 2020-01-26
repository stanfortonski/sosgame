<?php

	require_once '../../src/include.php';

	session_start();

	if (!SOS\Account::get()->isLogged())
		exit;

	echo SOS\PlayerManager::get()->makeGeneralThumbnail();

?>
