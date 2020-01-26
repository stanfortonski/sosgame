<?php

	namespace SOS
	{
		class Npc extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'NPCS';
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function getHeroManipulator(): Hero
			{
				$hero = new Hero;
				$hero->setId($this->selectThis(['id_hero']));
				return $hero;
			}

			public function getPositionManipulator(): Position
			{
				$position = new Position;
				$position->setId($this->selectThis(['id_position']));
				return $position;
			}
		}
	}

?>
