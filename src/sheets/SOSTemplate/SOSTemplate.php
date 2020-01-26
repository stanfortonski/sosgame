<?php

	namespace STV\Sheets
	{
		class SOSTemplate extends \STV\Sheet
		{
			public function render(\STV\Window &$window): void
			{
				$mainPath = \SOS\Config::get()->path('main');

				$window->setStyles([
					'https://fonts.googleapis.com/css?family=Fira+Sans:400,700&amp;subset=latin-ext',
					$mainPath.'css/bootstrap.min.css',
					$mainPath.'css/main.css',
					$mainPath.'css/components.css',
					$mainPath.'css/popup-box.css'
				]);

				$window->setScripts([
					$mainPath.'js/libs/jquery/jquery.min.js',
					$mainPath.'js/libs/bootstrap.bundle.min.js',
					$mainPath.'js/libs/jquery/plugins/popup-box.js',
					$mainPath.'js/libs/jquery/plugins/support.js',
					$mainPath.'js/libs/jquery/plugins/jquery-validation-1.16.0.min.js',
					$mainPath.'js/libs/jquery/plugins/jquery-validation-additional-1.16.0.min.js',
					$mainPath.'js/libs/js.cookie.min.js',
					$mainPath.'js/message.js',
					$mainPath.'js/init.js'
				]);

				$styles = $this->outputStyles($window->getStyles());
				$scripts = $this->outputScripts($window->getScripts());

				$this->makeUserSubmenu($window);

				require_once 'template.php';
			}

			protected function makeUserSubmenu(\STV\Window &$window): void
			{
				$prepend = '';
				$append = '';
				$public = \SOS\Config::get()->path('main');
				$panel = \SOS\Config::get()->path('panel');
				$game = \SOS\Config::get()->path('game');

				$window->content()->appendTo('icon', \SOS\Account::get()->getAvatar());

				if (\SOS\Account::get()->isLogged())
				{
					$userId = \SOS\Account::get()->getUserId();

					if (\SOS\Config::get()->path('actual') == $public)
						$prepend .=	'<a class="dropdown-item" href="'.$panel.'">Panel</a>';
					else $prepend .= '<a class="dropdown-item" href="'.$public.'">Strona Główna</a>';

					$prepend .= <<<EOF
					<a class="dropdown-item" href="{$game}">Graj</a>
					<a class="dropdown-item" href="{$public}profile?id={$userId}">Profil</a>
EOF;

					$append .= <<<EOF
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="logout">Wyloguj</a>
EOF;
				}
				else
				{
					$prepend .= '<a class="dropdown-item login-button" href="#">Zaloguj się</a>';
					$prepend .= '<a class="dropdown-item" href="join">Rejestracja</a>';
				}

				$window->content()->prependTo('user-navigation', $prepend);
				$window->content()->appendTo('user-navigation', $append);
			}
		}
	}

?>
