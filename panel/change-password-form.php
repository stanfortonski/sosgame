<?php

	require_once '../src/include.php';

	session_start();

	if (!SOS\Account::get()->isLogged())
	{
		header('location: goaway');
		exit;
	}

	$window = new STV\Window(new STV\Sheets\PanelPage);
	$window->setTitle('Zmień hasło');
	$window->setScripts(['forms/validation/change-password.form.js']);
	$window->content()->append('<div class="row"><div class="col"><article><h3>'.$window->getTitle().'</h3>'.SOS\Account::get()->makeChangePasswordForm().'</article></div></div>');

?>
