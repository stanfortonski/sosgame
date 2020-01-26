<?php

	require_once 'src/config.php';
	require_once 'src/content.php';
	require_once 'src/webdata.php';
	require_once 'src/window.php';
	require_once 'src/sheet.php';
	
	spl_autoload_register(function ($className)
	{
		$className = explode("\\", $className);
		$className = end($className);

		$file = dirname(__FILE__).STV\Config::$pathToSheets.'/sheets/'.strToLower($className).'/'.strToLower($className).'.php';
		if (file_exists($file))
			require_once $file;
	});

?>