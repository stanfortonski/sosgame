<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class PlayerTest extends TestCase
	{
		static $player;
		static $generalId;
		const NAME = 'lulek';

		public function testAddingPlayer(): void
		{
			self::$player = new SOS\Player;
			self::$player->setId(1);

			$idServer = 2;
			$idCharacter = 2;
			$name2 = 'LULEK';

			$this->assertTrue(self::$player->addGeneral($idServer, $idCharacter, self::NAME));
			$this->assertFalse(self::$player->canAddGeneral($idServer, self::NAME));
			$this->assertFalse(self::$player->canAddGeneral($idServer, $name2));

			self::$player->orderBY('id_general DESC');
			self::$player->limit(1);
			self::$generalId = self::$player->selectThis(['id_general']);
		}

		/**
     * @depends testAddingPlayer
     */
		public function testChangingNameOfPlayer(): void
		{
			$generalId = self::$player->getAllGeneralsIds()[1];
			$this->assertFalse(self::$player->changeGeneralName($generalId, self::NAME));
			$this->assertFalse(self::$player->changeGeneralName(-23, ''));
			$this->assertTrue(self::$player->changeGeneralName($generalId, self::NAME.self::NAME));
		}

		/**
     * @depends testAddingPlayer
     */
		public function testManipulatorGetters(): void
		{
			$amount = self::$player->getAmountOfGenerals();
			$this->assertEquals($amount, 2);
			$this->assertEquals(1, self::$player->getAmountOfGeneralsAtServer(1));
			$this->assertEquals(self::$generalId, self::$player->getGeneralsIdsAtServer(2)[0]);
			$this->assertEquals(self::$player->getAllGeneralsIds(), [1, self::$generalId]);

			$serversWithGenerals = self::$player->getServersWithGenerals();
			$this->assertEquals($serversWithGenerals[0][0], 1);
			$this->assertEquals($serversWithGenerals[1][0], 2);

			$this->assertEquals($serversWithGenerals[0][1], [1]);
			$this->assertEquals($serversWithGenerals[1][1], [self::$generalId]);

			$this->assertTrue(self::$player->hasGeneral(1));
			$general = self::$player->getGeneralManipulator(self::$generalId);
			$this->assertTrue($general != false);
			$this->assertFalse(self::$player->getGeneralManipulator(0));
		}

		/**
     * @depends testAddingPlayer
     */
		public function testRemovingAllPlayersData(): void
		{
			$generals = self::$player->getAllGeneralsIds();
			$this->assertTrue(self::$player->deleteAfterYouDidNotPlayForFifteenDays($generals[1]));
			$after = self::$player->getAmountOfGenerals();

			$this->assertEquals(count($generals) - $after, 1);
		}
	}

?>
