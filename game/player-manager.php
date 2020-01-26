<?php

	require_once '../src/include.php';

	session_start();

	if (empty($_SESSION['choose_gen']))
		header('location: index');

	$window = new STV\Window(new STV\Sheets\ChoosingPage);
	$window->setTitle('Wybierz postać');

	$generals = SOS\PlayerManager::get()->makeGeneralsList('choosing', 'Graj');
	$window->content()->append($generals);
?>
