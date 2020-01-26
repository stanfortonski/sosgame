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
					'main-local' => 'D:/xampp/htdocs/projects/sosgame/public/',

					'game' => 'http://play.sosgame.online/',
					'game-local' => 'D:/xampp/htdocs/projects/sosgame/game/',

					'panel' => 'http://panel.sosgame.online/',
					'panel-local' => 'D:/xampp/htdocs/projects/sosgame/panel/',

					'actual' => 'http://'.$serverName.'/',
					'actual-local' => $_SERVER['DOCUMENT_ROOT'].'/'
				];

				$this->database = [
					'host' => 'localhost',
					'dbname' => 'SOSGAME',
					'user' => 'root',
					'password' => ''
				];

				$this->mailAddress = 'nerotestero@gmail.com';
			}
		}

		\STV\Sheet::$path = Config::get()->path('actual');
		\DBManagement\DatabaseConnection::$database = Config::get()->dataBase();
	}

?>
