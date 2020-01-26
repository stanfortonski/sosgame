<?php

	namespace STV\Sheets
	{
		class StartPage extends SOSTemplate
		{
			public function render(\STV\Window &$window): void
			{
				$mainPath = \SOS\Config::get()->path('main');

				$window->setScripts(['https://www.google.com/recaptcha/api.js', $mainPath.'js/forms/login.js']);
				$window->setStyles([$mainPath.'css/startPage.css']);

				$window->content()->prependTo('navigation', file_get_contents(dirname(__FILE__).'/../publicPage/navigation.html'));

				if (\SOS\Account::get()->isLogged())
				{
					$sideContent = '<a href="'.\SOS\Config::get()->path('game').'" class="btn btn-lg btn-block" role="button">Graj</a>';
					$sideContent .= '<a href="'.\SOS\Config::get()->path('panel').'" class="btn btn-lg btn-block" role="button">Panel</a>';
				}
				else
				{
					$sideContent = <<<EOF
					<a href="#" class="btn btn-lg btn-block login-button" role="button">Zaloguj</a>
					<a href="join" class="btn btn-lg btn-block">Dołącz</a>
EOF;
				}
				$window->content()->prependTo('side-navigation', $sideContent);

				ob_start();
				require 'structure.php';
				$window->content()->prependTo('structure', ob_get_clean());

				parent::render($window);
			}
		}
	}

?>
