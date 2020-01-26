<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class TeamTest extends TestCase
	{
		public function testAdddingTeamMates(): void
		{
			$team = new SOS\Team;
			$team->setId(1);
			$team->setMaxSize(2);
			$team->clearEquipment();

			$this->assertEmpty($team->getAllReserve());
			$this->assertEquals($team->getAmountOfReserve(), 0);

			$team->addToReserve(1, true);
			$this->assertNotEmpty($team->getAllReserve());
			$this->assertEquals($team->getAmountOfReserve(), 1);

			$teamMate = $team->getFromReserve(1);
			$this->assertNotEmpty($teamMate);
			$this->assertTrue((bool)$teamMate['is_temp']);

			$wasUsed = $team->addItem(1, 1);
			$this->assertNotEmpty($wasUsed);
			$this->assertEquals($team->getAmountOfReserve(), 0);

			$teamMate = $team->getItem(1);
			$this->assertTrue((bool)$teamMate['is_temp']);

			$wasntUsed = $team->addItem(2, 1);
			$this->assertEmpty($wasntUsed);

			for ($i = 1; $i < 12; ++$i)
				$team->addToReserve($i, false);

			$this->assertEquals($team->getAmountOfReserve(), $team->getReserveMaxSize());

			$wasUsed = $team->addItem(2, 1);
			$this->assertNotEmpty($wasUsed);

			$teamMate = $team->getItem(2);
			$this->assertFalse((bool)$teamMate['is_temp']);
		}

		/**
     * @depends testAdddingTeamMates
     */
		public function testRemovingTeamMates(): void
		{
			$team = new SOS\Team;
			$team->setId(1);
			$team->setMaxSize(2);

			$team->removeFromReserve(1);
			$this->assertEquals($team->getAmountOfReserve(), $team->getReserveMaxSize()-1);

			$team->removeItem(1);
			$teamMate = $team->getItem(1);
			$this->assertEmpty($teamMate);
			$this->assertEquals($team->getAmountOfReserve(), $team->getReserveMaxSize());

			$team->removeItem(1);
			$teamMate = $team->getItem(1);
			$this->assertEmpty($teamMate);
			$this->assertEquals($team->getAmountOfReserve(), $team->getReserveMaxSize());
		}
	}

?>
