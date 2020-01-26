<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class StatsTest extends TestCase
	{
		static $stats;
		static $copyStats;

		public function testAdddingStats(): void
		{
			self::$stats = new SOS\Stats;
			self::$copyStats = new SOS\Stats;

			$newStatsId = self::$stats->addDefaultStats();
			self::$stats->setId($newStatsId);
			$fromDataBase = self::$stats->selectThis();
			$expected = [
				'id' => $newStatsId,
				 'hp_min' =>	0,
				 'hp_max' => 0,
				 'strength' => 0,
				 'magic_power' =>	0,
				 'dexterity' =>	0,
				 'critical_chance' =>	0,
				 'critical_strength' =>	0,
				 'escape' =>	0,
				 'counter' =>	0,
				 'energy' =>	0
				];

			$this->assertEquals($fromDataBase, $expected);

			self::$copyStats->setId(self::$stats->newStatsFromStats());
			$copyData = self::$copyStats->selectThis();

			$expected['id'] = self::$copyStats->getId();

			$this->assertEquals($copyData, $expected);
		}

		/**
     * @depends testAdddingStats
     */
		public function testAddingDamage(): void
		{
			$idDamage = 1;
			$valueMin = 20;
			$valueMax = 30;

			self::$stats->addDamage($idDamage, $valueMin, $valueMax);

			self::$stats->useTable('DAMAGE');
			self::$stats->where('id_type_damage = ?', [$idDamage]);
			$damage = self::$stats->selectThis();
			self::$stats->useDefaultTable();

			$this->assertEquals($damage['id_type_damage'], $idDamage);
			$this->assertEquals($damage['value_min'], $valueMin);
			$this->assertEquals($damage['value_max'], $valueMax);
		}

		/**
     * @depends testAdddingStats
     */
		public function testAddImmunity(): void
		{
			$idDamage = 1;
			$value = 20;

			self::$stats->addImmunity($idDamage, $value);

			self::$stats->useTable('IMMUNITIES');
			self::$stats->where('id_type_damage = ?', [$idDamage]);
			$immunity = self::$stats->selectThis();
			self::$stats->useDefaultTable();

			$this->assertEquals($immunity['id_type_damage'], $idDamage);
			$this->assertEquals($immunity['percentages'], $value);
		}

		/**
     * @depends testAdddingStats
     */
		public function testSelectingDamagesAndImmunities(): void
		{
			$idDamage = 1;
			$value = 20;
			$valueMax = 30;

			$damages = self::$stats->getDamages()[0];
			$immunities = self::$stats->getImmunities()[0];

			$this->assertEquals($immunities['id_type_damage'], $idDamage);
			$this->assertEquals($immunities['percentages'], $value);

			$this->assertEquals($damages['id_type_damage'], $idDamage);
			$this->assertEquals($damages['value_min'], $value);
			$this->assertEquals($damages['value_max'], $valueMax);
		}

		/**
     * @depends testAdddingStats
     */
		public function testUpdatingStatsWhenIsNextLevel(): void
		{
			$lvl = 20;
			$value = 20;

			$newHp = round($value + $value * SOS\Stats::HP_PER_LVL);
			$newEnergy = round($value + $value * SOS\Stats::ENERGY_PER_LVL);

			self::$stats->updateThis(['hp_max' => $value]);
			self::$stats->updateThis(['energy' => $value]);
			self::$stats->increaseStatsByLevel($lvl);

			$stats = self::$stats->selectThis();

			$this->assertEquals($stats['hp_max'], $newHp);
			$this->assertEquals($stats['energy'], $newEnergy);
		}

		/**
     * @depends testAdddingStats
     */
		public function testUpdatingStatsByUpdater(): void
		{
			$idUpdater = 1;

			self::$stats->useTable('STATS_UPDATER');
			$updater = self::$stats->selectById($idUpdater);
			self::$stats->useDefaultTable();

			$newStr = $updater['strength'];
			$newDex = $updater['dexterity'];
			$newPower = $updater['magic_power'];

			self::$stats->increaseStatsByUpdater($idUpdater);
			$stats = self::$stats->selectThis();

			$this->assertEquals($stats['strength'], $newStr);
			$this->assertEquals($stats['dexterity'], $newDex);
			$this->assertEquals($stats['magic_power'], $newPower);
		}

		/**
     * @depends testAdddingStats
     */
		public function testCalculatingDamage(): void
		{
			$defaultDamage = 100;
			$magicPower = 20;
			$strength = 20;
			$criticalStrength = 20;

			$calculateMagicDmg = SOS\Stats::getRealMagicDamage($defaultDamage, $magicPower);
			$calculateDmg = SOS\Stats::getRealDamage($defaultDamage, $strength);
			$calculateCriticalDmg = SOS\Stats::getCriticalDamage($defaultDamage, $criticalStrength);

			$this->assertEquals($calculateDmg, 106);
			$this->assertEquals($calculateMagicDmg, 110);
			$this->assertEquals($calculateCriticalDmg, 120);
		}

		/**
     * @depends testAdddingStats
     */
		public function testHealing(): void
		{
			$min = 10;
			$max = 100;

			self::$stats->updateThis(['hp_min' => $min, 'hp_max' => $max]);

			self::$stats->heal($min);
			$this->assertEquals(self::$stats->selectThis(['hp_min']), $min+$min);

			self::$stats->heal($max);
			$this->assertEquals(self::$stats->selectThis(['hp_min']), self::$stats->selectThis(['hp_max']));
		}

		/**
     * @depends testAdddingStats
     */
		public function testRemovingStatsData(): void
		{
			self::$stats->removeAllStatsData();
			self::$copyStats->removeAllStatsData();
			$this->assertEmpty(self::$stats->selectThis());
			$this->assertEmpty(self::$copyStats->selectThis());

			self::$stats->useTable('IMMUNITIES');
			$this->assertEmpty(self::$stats->selectArrayThis());

			self::$stats->useTable('DAMAGE');
			$this->assertEmpty(self::$stats->selectArrayThis());

			self::$stats->useDefaultTable();
		}
	}

?>
