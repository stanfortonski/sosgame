<?php

	namespace SOS
	{
		class Hero extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'HEROES';
			}

			static public function addHero(int $idCharacter): int
			{
				$character = new Character;
				$character->setId($idCharacter);
				$data = $character->selectThis(['lvl_min', 'id_default_stats', 'id_stats_updater']);

				$level = $data['lvl_min'];
				$idDefaultStats = $data['id_default_stats'];
				$idUpdater = $data['id_stats_updater'];

				$stats = new Stats;
				$stats->setId($idDefaultStats);
				$idStats = $stats->newStatsFromStats();

				$stats->setId($idStats);
				$stats->increaseStatsByUpdater($idUpdater);

				return static::insertThat([null, $idCharacter, $idStats, $level, 0]);
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function addExp(int $exp): void
			{
				$yourExp = $this->selectThis(['exp']);
				$this->updateThis(['exp' => $yourExp+$exp]);
			}

			public function nextLevel(int $expPerLevel): bool
			{
				$properties = $this->selectThis(['lvl', 'exp']);

				$neededExp = $expPerLevel * $properties['lvl'];
				if ($neededExp <= $properties['exp'])
				{
					$exp = $properties['exp'] - $neededExp;
					$lvl = $properties['lvl'] + 1;

					$this->updateThis(['exp' => $exp, 'lvl' => $lvl]);

					$character = $this->getCharacterManipulator();
					$updaterId = $character->selectThis(['id_stats_updater']);

					$stats = $this->getStatsManipulator();
					$stats->increaseStatsByUpdater($updaterId);
					$stats->increaseStatsByLevel($lvl);
					return true;
				}
				return false;
			}

			public function getCharacterManipulator(): Character
			{
				$character = new Character;
				$character->setId($this->selectThis(['id_character']));
				return $character;
			}

			public function getStatsManipulator(): Stats
			{
				$stats = new Stats;
				$stats->setId($this->selectThis(['id_stats']));
				return $stats;
			}

			public function getEqInUseManipulator(): EquipmentInUse
			{
				$eq = new EquipmentInUse;
				$eq->setId($this->getId());
				return $eq;
			}

			public function removeAllHeroData(): void
			{
				$this->getStatsManipulator()->removeAllStatsData();
				$this->getEqInUseManipulator()->clear();
				$this->deleteThis();
			}
		}
	}

?>
