<?php

	require_once '../src/include.php';

	session_start();

	if (!SOS\Account::get()->isLogged())
	{
		header('location: goaway');
		exit;
	}

	$window = new STV\Window(new STV\Sheets\PanelPage);
	$window->setTitle('Edycja postaci');
	$window->setScripts(['forms/validation/change-player-name.form.js']);
	$window->content()->appendTo('breadcrumb', '<li class="breadcrumb-item"><a href="player-manager">Menadżer postaci</a></li>');
	$window->content()->append(SOS\PlayerManager::get()->makeEditGeneralForm());

?>
