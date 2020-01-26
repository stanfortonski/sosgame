<?php

	namespace STV\Sheets
	{
		class ChoosingPage extends SOSTemplate
		{
			public function render(\STV\Window &$window): void
			{
				$window->setStyles([
					'player-manager.css',
				]);

				$window->setScripts([
					'forms/chooseGeneral.js'
				]);

				ob_start();
				require 'structure.php';
				$window->content()->prependTo('structure', ob_get_clean());

				parent::render($window);
			}
		}
	}

?>
