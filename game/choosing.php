<?php

	require_once '../src/include.php';

	session_start();

	if (!SOS\Account::get()->isLogged())
	{
		header('location: '.SOS\Config::get()->path('main'));
		exit;
	}
	else if (SOS\Account::get()->isVerified() && !empty($_POST['generalAndServer']))
	{
		$obj = json_decode($_POST['generalAndServer']);
		$_SESSION['general-id'] = $obj->general;
		$_SESSION['server-id'] = $obj->server;

		header('location: game');
		exit;
	}
	else
	{
		header('location: '.SOS\Config::get()->path('panel'));
		exit;
	}

?>
