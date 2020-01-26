<?php

	require_once '../src/include.php';

	session_start();

	$window = new STV\Window(new STV\Sheets\PublicPage);
	$window->setTitle('Regulamin gry przeglądarkowej - mobilnej | SOSGAME');
	$window->content()->append('<div class="row"><article class="col"><h3>Regulamin</h3></article></div>');

?>
