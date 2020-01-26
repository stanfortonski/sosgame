<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class MobTest extends TestCase
	{
		static $mob;

		private function beforeStartTests(): void
		{
			self::$mob = new SOS\Mob;
			self::$mob->setId(1);
			self::$mob->setServerId(1);
			self::$mob->setGroupId(1);
		}

		public function testKillingMob(): void
		{
			$this->beforeStartTests();
			$stats = self::$mob->getHeroManipulator()->getStatsManipulator();
			$hpMax = $stats->selectThis(['hp_max']);
			$stats->updateThis(['hp_min' => 0]);

			$this->assertFalse(self::$mob->isDied());

			self::$mob->kill(1);
			$this->assertEquals($hpMax, $stats->selectThis(['hp_min']), 'Hp correct');
			$this->assertTrue(self::$mob->isDied());
		}

		/**
		 * @depends testKillingMob
		 */
		public function testRespawningMob(): void
		{
			SOS\Mob::respawnMobs();
			$this->assertTrue(self::$mob->isDied());

			self::$mob->useTable('MOBS_DIES');
			self::$mob->where('id_mob = ? AND id_server = ? AND id_group = ?', [self::$mob->getId(), self::$mob->getServerId(), self::$mob->getGroupId()]);
			self::$mob->update(['end_date' => date('Y-m-d H:i:s')]);
			self::$mob->useDefaultTable();

			SOS\Mob::respawnMobs();
			$this->assertFalse(self::$mob->isDied());
		}
	}

?>
