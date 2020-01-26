<?php

	namespace STV\Sheets
	{
		class PublicPage extends SOSTemplate
		{
			public function render(\STV\Window &$window): void
			{
				$mainPath = \SOS\Config::get()->path('main');

				$window->setScripts([$mainPath.'js/message.js', 'https://www.google.com/recaptcha/api.js', $mainPath.'js/forms/login.js']);

				$window->content()->prependTo('navigation', file_get_contents(dirname(__FILE__).'/navigation.html'));

				ob_start();
				require 'structure.php';
				$window->content()->prependTo('structure', ob_get_clean());

				parent::render($window);
			}
		}
	}

?>
