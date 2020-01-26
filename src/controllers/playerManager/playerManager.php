<?php

	namespace SOS
	{
		class PlayerManager
		{
			use PlayerAdding, PlayerChoosing, PlayerEditing;

			static private $instance;
			private $view;
			private $player;

			static public function get(): self
			{
				if (empty(static::$instance))
					static::$instance = new static(new PlayerView);
				return static::$instance;
			}

			protected function __construct(PlayerView $view)
			{
				$this->view = $view;
				$this->player = new Player;
				if (Account::get()->getUserId())
				$this->player->setId(Account::get()->getUserId());
			}

			public function changeView(PlayerView $view): void
			{
				$this->view = $view;
			}

			public function setId(int $id): void
			{
				$this->player->setId($id);
			}

			public function getId(): int
			{
				return $this->player->getId();
			}
		}
	}

?>
