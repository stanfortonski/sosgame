<?php

	require_once '../src/include.php';

	session_start();

	if (SOS\Account::get()->isLogged())
	{
		header('location: '.SOS\Config::get()->path('panel'));
		exit;
	}

	$window = new STV\Window(new STV\Sheets\PublicPage);
	$window->setTitle('Rejestracja');
	$window->setScripts(['forms/validation/register.form.js']);
	$window->content()->append(SOS\Account::get()->makeRegisterForm());
	
?>
