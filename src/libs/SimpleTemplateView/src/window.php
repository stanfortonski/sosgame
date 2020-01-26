<?php

	namespace STV
	{
		class Window extends WebData
		{
			private $isShow = false;
			private $sheet;
			private $content;

			public function __construct(Sheet $sheet)
			{
				$this->sheet = $sheet;
				$this->content = $this->makeContent();
			}

			protected function makeContent(): Content
			{
				return new Content;
			}

			public function __destruct()
			{
				$this->display();
			}

			public function display(): void
			{
				if (!$this->isShow)
				{
					$this->sheet->render($this);
					$this->isShow = true;
				}
			}

			public function content(): Content
			{
				return $this->content;
			}
		}
	}
?>
