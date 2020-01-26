<?php

	require_once '../../src/include.php';

	session_start();

	if (SOS\Account::get()->remind())
		echo 'Wiadomość z nowym hasłem została wysłana na pocztę.';
	else echo SOS\Account::get()->makeRemindForm();

?>
