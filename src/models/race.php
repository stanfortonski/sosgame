<?php

	namespace SOS
	{
		class Race extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'RACES';
			}

			static public function getPath(): string
			{
				return Config::get()->path('game').'imgs/races/';
			}

			public function __construct()
			{
				parent::__construct();
			}
		}
	}

?>
