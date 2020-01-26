<?php

	spl_autoload_register(function (string $className)
	{
		$className = explode("\\", $className);
		$className = end($className);

		$file = dirname(__FILE__).'/'.strToLower($className).'.php';

		if (file_exists($file))
			require_once $file;
	});

?>
