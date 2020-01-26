<?php

	require_once '../../src/include.php';

	session_start();

	echo SOS\Account::get()->makeRemindForm();
	echo '<script src="js/forms/validation/remind.form.js"></script>';

?>
