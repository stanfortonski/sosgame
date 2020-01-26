<?php

	require_once '../src/include.php';
	
	session_start();
	
	header('location: '.SOS\Config::get()->path('main').'logout');
	
?>