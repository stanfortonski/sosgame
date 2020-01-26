<?php

	require_once '../src/include.php';

	session_start();

	$window = new STV\Window(new STV\Sheets\StartPage);
	$window->setTitle('Gra przeglądarkowa MMORPG | SOSGAME');

	$articlesController = new SOS\ArticlesController(new SOS\ArticleView);
	$window->content()->append($articlesController->makeAllPerPage());

	$navbar = $articlesController->makeNavBar();
	$DOM = new DOMDocument;
	libxml_use_internal_errors(true);
	$DOM->loadHTML($navbar);

	if (!empty($prev = $DOM->getElementById('navBarPrev')))
		$window->content()->appendTo('meta', '<link rel="prev" href="'.$prev->parentNode->getAttribute('href').'">');

	if (!empty($next = $DOM->getElementById('navBarNext')))
		$window->content()->appendTo('meta', '<link rel="next" href="'.$next->parentNode->getAttribute('href').'">');

	libxml_clear_errors();

	$window->content()->append($navbar);

?>
