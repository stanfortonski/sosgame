<?php

	require_once '../src/include.php';

	session_start();

	$window = new STV\Window(new STV\Sheets\PublicPage);
	$window->setTitle('Artykuł');

	if (empty($_GET['id']) || !is_numeric($_GET['id']))
	{
		header('location: error?type=404');
		exit;
	}

	$articlesController = new SOS\ArticlesController(new SOS\ArticleView);
	$article = $articlesController->makeOne($_GET['id']);
	if ($article == '')
	{
		header('location: error?type=404');
		exit;
	}
	$window->content()->append($article.'<a href="/" class="btn btn-lg">Powrót</a>');

?>
