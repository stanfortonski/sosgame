<?php

	require_once '../src/include.php';

	session_start();

	if (!SOS\Account::get()->isLogged())
	{
		header('location: goaway');
		exit;
	}

	$window = new STV\Window(new STV\Sheets\PanelPage);
	$window->setTitle('Panel');
	$window->content()->append(SOS\Account::get()->makePanel());

?>
