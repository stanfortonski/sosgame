<?php

	namespace SOS
	{
		class Stats extends DatabaseOperationsAdapter
		{
			public const HP_PER_LVL = 0.2;
			public const ENERGY_PER_LVL = 0.1;
			public const MAGIC_POWER_MULTIPLIER = 0.5;
			public const STRENGTH_MULTIPLIER = 0.3;

			static public function getTable(): string
			{
				return 'STATS';
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function increaseStatsByUpdater(int $idUpdater): void
			{
				$stats = $this->selectThis(['strength', 'magic_power', 'dexterity']);
				$updater = $this->getUpdater($idUpdater);
				$this->updateThis([
					'strength' => $stats['strength'] + $updater['strength'],
					'magic_power' => $stats['magic_power'] + $updater['magic_power'],
					'dexterity' => $stats['dexterity'] + $updater['dexterity']
				], true);
			}

			public function increaseStatsByLevel(int $level): void
			{
				$stats = $this->selectThis(['hp_max', 'energy']);
				$this->updateThis([
					'hp_max' => self::calcMaxHP($stats['hp_max']),
					'energy' => self::calcEnergy($stats['energy'])
				]);
			}

			public function heal(int $value = -1): void
			{
				$data = $this->selectThis(['hp_min', 'hp_max']);
				$addHp = ($value == -1) ? $data['hp_max'] : $data['hp_min'] + $value;
				if ($addHp > $data['hp_max'])
					$addHp = $data['hp_max'];
				$this->updateThis(['hp_min' => $addHp]);
			}

			public function newStatsFromStats(): int
			{
				$statsData = $this->selectThis();
				$statsData['id'] = null;
				return $this->insert(array_values($statsData));
			}

			public function getDamages(): array
			{
				$this->useTable('DAMAGE');
				$damages = $this->selectArrayThis();
				$this->useDefaultTable();
				return $damages;
			}

			public function getImmunities(): array
			{
				$this->useTable('IMMUNITIES');
				$immunities = $this->selectArrayThis();
				$this->useDefaultTable();
				return $immunities;
			}

			public function getNamesOfDamages(): array
			{
				$this->useTable('TYPES_OF_DAMAGE');
				$names = $this->selectArray(['name']);
				$this->useDefaultTable();
				return $names;
			}

			public function getUpdatersNames(): array
			{
				$this->useTable('STATS_UPDATER');
				$names = $this->selectArray(['name']);
				$this->useDefaultTable();
				return $names;
			}

			private function getUpdater(int $idUpdater): array
			{
				$this->useTable('STATS_UPDATER');
				$updater = $this->selectById($idUpdater);
				$this->useDefaultTable();
				return $updater;
			}

			public function addDamage(int $idDamageType, int $min, int $max): void
			{
				$this->useTable('DAMAGE');
				$this->insert([$this->getId(), $idDamageType, $min, $max]);
				$this->useDefaultTable();
			}

			public function addImmunity(int $idDamageType, int $procents): void
			{
				$this->useTable('IMMUNITIES');
				$this->insert([$this->getId(), $idDamageType, $procents]);
				$this->useDefaultTable();
			}

			public function removeDamage(int $idDamageType): void
			{
				$this->useTable('DAMAGE');
				$this->where('id_type_damage = ?', [$idDamageType]);
				$this->delete();
				$this->useDefaultTable();
			}

			public function removeImmunity(int $idDamageType): void
			{
				$this->useTable('IMMUNITIES');
				$this->where('id_type_damage = ?', [$idDamageType]);
				$this->delete();
				$this->useDefaultTable();
			}

			public function removeAllStatsData()
			{
				$this->deleteThis();

				$this->useTable('DAMAGE');
				$this->deleteThis();

				$this->useTable('IMMUNITIES');
				$this->deleteThis();

				$this->useDefaultTable();
			}

			static public function addDefaultStats(): int
			{
				return static::insertThat([null, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]);
			}

			static public function getChance(int $value): bool
			{
				return $value >= rand(1, 100);
			}

			static public function getDamage(int $min, int $max): int
			{
				return rand($min, $max);
			}

			static public function getRealMagicDamage(int $damage, int $magicPower): int
			{
				return round($damage + $magicPower * self::MAGIC_POWER_MULTIPLIER);
			}

			static public function getRealDamage(int $damage, int $strength): int
			{
				return round($damage + $strength * self::STRENGTH_MULTIPLIER);
			}

			static public function getCriticalDamage(int $damage, int $criticalStrength): int
			{
				return round($damage + $damage * ($criticalStrength * 0.01));
			}

			static private function calcEnergy(int $energy): int
			{
				return $energy + $energy * self::ENERGY_PER_LVL;
			}

			static private function calcMaxHP(int $maxHp) : int
			{
				return $maxHp + $maxHp * self::HP_PER_LVL;
			}
		}
	}

?>
