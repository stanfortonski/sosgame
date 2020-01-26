<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class GroupOfMobsTest extends TestCase
	{
		static $group;

		private function clearTestBeforeStart(): void
		{
			self::$group = new SOS\GroupOfMobs;
			self::$group->setId(1);
			self::$group->setServerId(1);

			self::$group->useTable('GROUP_OF_MOBS_ELIMINATED');
			self::$group->delete();
			self::$group->useDefaultTable();
		}

		public function testGettingGroupOfMobs(): void
		{
			$this->clearTestBeforeStart();
			$mobs = self::$group->getMobs();

			$this->assertEquals(self::$group->getSizeOfGroup(), 3);
			$this->assertEquals(count($mobs), 3);

			$mob = new SOS\Mob;
			$mob->setId($mobs[2]['id_mob']);
			$mob->setServerId(self::$group->getServerId());
			$mob->setGroupId(self::$group->getId());
			$mob->kill(1);
			$this->assertEquals(count(self::$group->getAliveMobs()), 2);

			self::$group->setServerId(2);
			$this->assertEquals(count(self::$group->getAliveMobs()), 3);
			self::$group->setServerId(1);
		}

		/**
     * @depends testGettingGroupOfMobs
     */
		public function testEliminatingGroupOfMobs(): void
		{
			$this->assertFalse(self::$group->isEliminate());
			self::$group->eliminate();
			$this->assertTrue(self::$group->isEliminate());
		}

		/**
     * @depends testGettingGroupOfMobs
     */
		public function testRespawningGroupOfMobs(): void
		{
			SOS\GroupOfMobs::respawnGroups();
			$this->assertTrue(self::$group->isEliminate());

			self::$group->useTable('GROUP_OF_MOBS_ELIMINATED');
			self::$group->where('id_server = ? AND id_group = ?', [self::$group->getServerId(), self::$group->getId()]);
			self::$group->update(['end_date' => date('Y-m-d H:i:s')]);
			self::$group->useDefaultTable();

			SOS\GroupOfMobs::respawnGroups();

			$this->assertFalse(self::$group->isEliminate());
			$this->assertEquals(count(SOS\GroupOfMobs::getAliveGroups(1)), 1);
			$this->assertEquals(count(SOS\GroupOfMobs::getAliveGroups(2)), 1);
		}
	}

?>
