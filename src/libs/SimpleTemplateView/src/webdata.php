<?php

	namespace STV
	{
		class WebData
		{
			private $title = '';
			private $styles = [];
			private $scripts = [];

			public function getTitle(): string
			{
				return $this->title;
			}

			public function getStyles(): array
			{
				return $this->styles;
			}

			public function getScripts(): array
			{
				return $this->scripts;
			}

			public function setTitle(string $title): void
			{
				$this->title = $title;
			}

			public function setStyles(array $hrefs): void
			{
				$this->styles = array_merge($hrefs, $this->styles);
			}

			public function setScripts(array $srcs): void
			{
				$this->setScriptsAfter($srcs);
			}

			public function setScriptsAfter(array $srcs): void
			{
				$this->scripts = array_merge($srcs, $this->scripts);
			}

			public function setScriptsBefore(array $srcs): void
			{
				$this->scripts = array_merge($this->scripts, $srcs);
			}
		}
	}

?>
