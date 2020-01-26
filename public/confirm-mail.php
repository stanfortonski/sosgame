<?php

	require_once '../src/include.php';

	session_start();

	SOS\Account::get()->confirmNewMail();

?>
