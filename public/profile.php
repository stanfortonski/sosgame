<?php

	require_once '../src/include.php';

	session_start();

	$window = new STV\Window(new STV\Sheets\PublicPage);
	$window->setTitle('Profil');
	$window->setStyles([SOS\Config::get()->path('panel').'css/thumbnails.css']);
	$window->content()->append(SOS\Account::get()->makeProfile($_GET['id']));

?>
