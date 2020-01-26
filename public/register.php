<?php

	require_once '../src/include.php';

	session_start();

	if (SOS\Account::get()->isLogged())
	{
		header('location: panel');
		exit;
	}
	SOS\Account::get()->register();

?>
