<?php

	namespace STV\Sheets
	{
		class PanelPage extends SOSTemplate
		{
			public function render(\STV\Window &$window): void
			{
				$window->setStyles(['main.css', 'components.css', 'thumbnails.css']);
				$window->content()->appendTo('meta', '<meta name="robots", content="noindex">');
				$window->content()->prependTo('navigation', file_get_contents(dirname(__FILE__).'/navigation.html'));

				ob_start();
				require 'structure.php';
				$window->content()->prependTo('structure', ob_get_clean());

				parent::render($window);
			}
		}
	}

?>
