<?php

	require_once '../../src/include.php';

	session_start();

	echo SOS\Account::get()->makeLoginForm();
	echo '<script src="js/forms/validation/authorize.form.js"></script>';

?>
