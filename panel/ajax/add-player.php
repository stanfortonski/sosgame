<?php

	require_once '../../src/include.php';

	session_start();

	if (SOS\Account::get()->isLogged())
	{
		if (SOS\PlayerManager::get()->addGeneral())
			echo '{"message": "Postać została dodana pomyślnie."}';
		else echo '{"error": "Nie udało się dodać postaci."}';
	}

?>
