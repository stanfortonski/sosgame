<?php

	namespace STV
	{
		class Content
		{
			private $contents = ['main' => ''];

			public function append(string $content): void
			{
				$this->appendTo('main', $content);
			}

			public function prepend(string $content): void
			{
				$this->prependTo('main', $content);
			}

			public function appendTo(string $name, string $content): void
			{
				if (empty($this->contents[$name]))
					$this->contents[$name] = $content;
				else $this->contents[$name] .= $content;
			}

			public function prependTo(string $name, string $content): void
			{
				if (empty($this->contents[$name]))
					$this->contents[$name] = $content;
				else $this->contents[$name] = $content.$this->contents[$name];
			}

			public function clear(): void
			{
				$this->contents['main'] = '';
			}

			public function clearIn(string $name): void
			{
				if (isset($this->contents[$name]))
					unset($this->contents[$name]);
			}

			public function get(string $name): string
			{
				return empty($this->contents[$name]) ? '' : $this->contents[$name];
			}

			public function getAll(): string
			{
				return $this->contents;
			}
		}
	}

?>
