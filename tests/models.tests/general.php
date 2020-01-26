<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class GeneralTest extends TestCase
	{
		static $general;

		public function testAddingGeneral(): void
		{
			$idChatacter = 1;
			$name = 'Papaj';
			$outfit = 1;

			self::$general = new SOS\General;
			$idGeneral = self::$general->addGeneral($idChatacter, $name);
			self::$general->setId($idGeneral);
			$general = self::$general->selectThis();

			$position = self::$general->getPositionManipulator()->selectThis();
			$hero = self::$general->getHeroManipulator()->selectThis();

			$this->assertNotEmpty($general);
			$this->assertEquals($general['name'], $name);
			$this->assertEquals($general['id_outfit'], $outfit);
			$this->assertNotEmpty($position);
			$this->assertNotEmpty($hero);
		}

		/**
     * @depends testAddingGeneral
     */
		public function testListsOfEnemiesAndFriends(): void
		{
			$this->assertEmpty(self::$general->getEnemies());

			self::$general->addEnemy(5);
			self::$general->addEnemy(2);

			$enemies = self::$general->getEnemies();
			$this->assertEquals(count($enemies), 2);
			$this->assertEquals($enemies[1], 5);
			$this->assertEquals($enemies[0], 2);

			self::$general->removeEnemy(5);

			$enemies = self::$general->getEnemies();
			$this->assertEquals(count($enemies), 1);
			$this->assertEquals($enemies[0], 2);

			self::$general->removeEnemy(2);
			$this->assertEmpty(self::$general->getEnemies());
		}

		/**
     * @depends testAddingGeneral
     */
		public function testNextGeneralsLevel(): void
		{
			$exp = 1000;
			$oldLearnPoints = self::$general->selectThis(['learnpoints']);

			self::$general->getHeroManipulator()->addExp($exp);
			$this->assertTrue(self::$general->nextLevel($exp));

			$newLearnPoints = self::$general->selectThis(['learnpoints']);
			$this->assertEquals($newLearnPoints, $oldLearnPoints+1);
		}

		/**
     * @depends testAddingGeneral
     */
		public function testKillingGeneral(): void
		{
			$position = self::$general->getPositionManipulator();
			$position->updateThis(['id_map' => 23]);
			$oldPos = $position->selectThis();

			$this->assertFalse(self::$general->isDied());
			self::$general->kill();
			$this->assertTrue(self::$general->isDied());

			$newPos = $position->selectThis();
			$this->assertNotEquals($oldPos, $newPos);
		}

		/**
     * @depends testAddingGeneral
     */
		public function testRespawningGeneral(): void
		{
			$stats = self::$general->getHeroManipulator()->getStatsManipulator();
			$stats->updateThis(['hp_min' => 0]);
			$hpMax = $stats->selectThis(['hp_max']);

			$this->assertTrue(self::$general->remainedToRespawnInSeconds() >= 30);
			$this->assertFalse(self::$general->respawn());

			self::$general->useTable('GENERALS_DIES');
			self::$general->updateThis(['end_date' => date('Y-m-d H:i:s')]);
			self::$general->useDefaultTable();

			$this->assertTrue(self::$general->respawn());
			$this->assertFalse(self::$general->isDied());
			$this->assertFalse(self::$general->respawn());
			$this->assertEquals($hpMax, $stats->selectThis(['hp_min']));
		}

		/**
     * @depends testAddingGeneral
     */
		public function testRemovingGeneralsData(): void
		{
			$positionManip = self::$general->getPositionManipulator();
			$heroManip = self::$general->getPositionManipulator();
			self::$general->removeAllGeneralData();

			$this->assertEmpty(self::$general->selectThis());
			$this->assertEmpty($positionManip->selectThis());
			$this->assertEmpty($heroManip->selectThis());
		}
	}

?>
