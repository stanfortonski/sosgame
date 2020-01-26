<?php

	namespace STV
	{
		abstract class Sheet
		{
			static public $path = '';

			protected function outputStyles(array $styles): string
			{
				$outputHTML = '';
				foreach ($styles as $style)
				{
					$path = static::$path.'css/'.$style;
					if (preg_match('/^((https:\/\/)|(http:\/\/)|(www.)).*/i', $style))
						$path = $style;
					$outputHTML .= '<link rel="stylesheet" href="'.$path.'">';
				}
				return $outputHTML;
			}

			protected function outputScripts(array $scripts): string
			{
				$outputHTML = '';
				foreach ($scripts as $script)
				{
					$path = static::$path.'js/'.$script;
					if (preg_match('/^((https:\/\/)|(http:\/\/)|(www.)).*/i', $script))
						$path = $script;
					$outputHTML .= '<script src="'.$path.'"></script>';
				}
				return $outputHTML;
			}

			abstract public function render(Window &$window): void;
		}
	}

?>
