<?php

	require_once '../src/include.php';

	session_start();

	if (!SOS\Account::get()->isLogged())
	{
		header('location: goaway');
		exit;
	}

	$window = new STV\Window(new STV\Sheets\PanelPage);
	$window->setScripts(['forms/delete-account.js']);
	$window->setTitle('Usuwanie konta');
	$window->content()->append('<div class="row"><div class="col"><article>'.SOS\Account::get()->makeDeleteOrBackForm().'</article></div></div>');

?>
