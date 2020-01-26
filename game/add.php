<?php

	require_once '../src/include.php';
	
	session_start();
	
	if (empty($_SESSION['add_gen'])) header('location: index');
	else header('location: '.SOS\Config::get()->path('main').'add-player-form');
	
?>