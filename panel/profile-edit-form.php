<?php

	require_once '../src/include.php';

	session_start();

	if (!SOS\Account::get()->isLogged())
	{
		header('location: goaway');
		exit;
	}

	$window = new STV\Window(new STV\Sheets\PanelPage);
	$window->setTitle('Edycja profilu');
  $window->setScripts(['forms/validation/profile-editor.form.js', 'forms/profile-editor.js']);
	$window->content()->append(SOS\Account::get()->makeProfileEditor());

?>
