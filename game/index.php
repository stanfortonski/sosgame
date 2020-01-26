<?php

	require_once '../src/include.php';

	session_start();

	if (!SOS\Account::get()->isLogged() || !SOS\Account::get()->isVerified())
	{
		header('location: '.SOS\Config::get()->path('main'));
		exit;
	}

	$player = new SOS\Player;
	$player->setId(SOS\Account::get()->getUserId());
	$amount = $player->getAmountOfGenerals();
	if ($amount > 0)
	{
		$_SESSION['choose_gen'] = true;
		header('location: player-manager');
		exit;
	}
	else
	{
		$_SESSION['add_gen'] = true;
		header('location: '.SOS\Config::get()->path('panel').'add-player-form');
		exit;
	}

?>
