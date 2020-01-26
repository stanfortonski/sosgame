<?php

	namespace SOS
	{
		class Character extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'CHARACTERS';
			}

			static public function addCharacter(array $data): int
			{
				return static::insertThat([
					null,
					Stats::addDefaultStats(),
					$data['id_stats_updater'],
					$data['id_outfit'],
					$data['is_temp'],
					$data['name'],
					$data['targets'],
					$data['range'],
					$data['size_in_team'],
					$data['cost'],
					$data['coins'],
					$data['lvl_min'],
					$data['lvl_max']
				]);
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function getStatsManipulator()
			{
				$stats = new Stats;
				$stats->setId($this->selectThis(['id_default_stats']));
				return $stats;
			}

			public function getOutfitManipulator()
			{
				$outfit = new Outfit;
				$outfit->setId($this->selectThis(['id_outfit']));
				return $outfit;
			}

			public function getWeapons(): array
			{
				$this->useTable('WEAPONS_FOR_CHARACTERS');
				$this->where('id_character = ?', [$this->getId()]);
				$weapons = $this->selectArray(['id_typeitem']);
				$this->useDefaultTable();
				return $weapons;
			}

			public function getPromotions(): array
			{
				$this->useTable('PROMOTIONS_CHARACTERS');
				$this->where('id_character = ?', [$this->getId()]);
				$promotions = $this->selectArray();
				$this->useDefaultTable();
				return $promotions;
			}

			public function getDefaultOutfitData(): array
			{
				return ['id_outfit' => $this->selectThis(['id_outfit']), 'coins' => 0];
			}

			public function removeAllCharacterData(): void
			{
				$stats = $this->getStatsManipulator();
				$stats->removeAllStatsData();

				$this->deleteThis();
			}
		}
	}

?>
