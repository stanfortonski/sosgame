<?php

	require_once 'libs/SimpleTemplateView/autoload.php';
	require_once 'libs/DatabaseManagement/autoload.php';
	require_once 'libs/MailSender/src.php';
	require_once 'config.php';
	require_once 'supports.php';

	spl_autoload_register(function (string $className){
		$className = explode("\\", $className);
		$className = end($className);
		$dirs = ['models', 'controllers/account', 'controllers/playerManager', 'views', 'controllers', 'mediators', 'mediators/extensions'];
		$path = dirname(__FILE__);

		foreach ($dirs as $dir)
		{
			$file = [strToLower("$path/$dir/$className.php"), strToLower("$path/$dir/$className/$className.php")];

			if (file_exists($file[0]))
			{
				require_once $file[0];
				break;
			}
			else if (file_exists($file[1]))
			{
				require_once $file[1];
				break;
			}
		}
	});

?>
