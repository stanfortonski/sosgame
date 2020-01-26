<?php

	namespace SOS
	{
		class Map extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'MAPS';
			}

			static public function getWorldsDirectory(): string
			{
				return 'worlds/';
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function generateMap(): string
			{
				$map = $this->selectThis();
				$userId = 1;

				ob_start();
				require_once Config::get()->path('game-local').self::getWorldsDirectory().$map['map_file'];
				$world = ob_get_clean();
				return $world;
			}

			public function getBackgroundMusic(): array
			{
				$this->useTable('MUSIC_IN_MAPS');
				$this->where('id_map = ?', [$this->getId()]);
				$music = $this->selectArray(['src']);
				$this->useDefaultTable();
				return $music;
			}
		}
	}

?>
