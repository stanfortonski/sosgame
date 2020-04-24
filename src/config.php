<?php

	namespace SOS
	{
		class Config
		{
			static private $instance;
			private $path;
			private $database;
			private $mailAddress;

			static public function get(): self
			{
				if (self::$instance == null)
					self::$instance = new self;
				return self::$instance;
			}

			public function dataBase(): array
			{
				return $this->database;
			}

			public function path(string $name): string
			{
				if (isset($this->path[$name]))
					return $this->path[$name];
				return '';
			}

			public function mail(): string
			{
				return $this->mailAddress;
			}

			private function __construct()
			{
				$serverName = empty($_SERVER['SERVER_NAME']) ? '' : $_SERVER['SERVER_NAME'];

				$this->path = [
					'main' => 'http://sosgame.online/',
					'main-local' => 'Your path to main via apache vhosts',

					'game' => 'http://play.sosgame.online/',
					'game-local' => 'Your path to game via apache vhosts',

					'panel' => 'http://panel.sosgame.online/',
					'panel-local' => 'Your path to panel via apache vhosts',

					'actual' => 'http://'.$serverName.'/',
					'actual-local' => $_SERVER['DOCUMENT_ROOT'].'/'
				];

				$this->database = [
					'host' => 'localhost',
					'dbname' => 'SOSGAME',
					'user' => 'root',
					'password' => ''
				];

				$this->mailAddress = 'Your mail address';
			}
		}

		\STV\Sheet::$path = Config::get()->path('actual');
		\DBManagement\DatabaseConnection::$database = Config::get()->dataBase();
	}

?>
