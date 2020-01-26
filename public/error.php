<?php

	require_once '../src/include.php';

	session_start();

	$type = isset($_GET['type']) ? $_GET['type'] : '';
	switch ($type)
	{
		case 400: $name = 'Złe żądanie'; break;
		case 403: $name = 'Zabroniony dostęp'; break;
		case 404: $name = 'Nie znaleziono strony'; break;
		case 500: $name = 'Błąd po stronie serwera'; break;
		default:
		{
			$type = '';
			$name = 'Nieoczekiwany błąd';
		}
	}

	$window = new STV\Window(new STV\Sheets\PublicPage);
	$window->setTitle('Wystąpił błąd '.$type);
	$window->content()->appendTo('meta', '<meta name="robots" content="noindex">');
	$window->content()->append('<div class="row"><article class="col"><h3>'.$name.'</h3>');
	$window->content()->append('Przepraszamy za zainstniałe problemy.</article></div>');
	$window->content()->append('<a href="/" class="btn btn-lg">Powrót</a>');

?>
