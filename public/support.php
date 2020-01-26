<?php

	require_once '../src/include.php';

	session_start();

	$window = new STV\Window(new STV\Sheets\PublicPage);
	$window->setTitle('Support');
	$window->content()->append('W budowie');

?>
