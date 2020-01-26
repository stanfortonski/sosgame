<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class PositionTest extends TestCase
	{
		static $position1;
		static $position2;

		public function beforeStartTests(): void
		{
			self::$position1 = new SOS\Position;
			self::$position2 = new SOS\Position;
		}

		public function testAddingPosition(): void
		{
			$this->beforeStartTests();
			$newPositionId = SOS\Position::insertThat([null, 1, 2, 2]);
			self::$position1->setId($newPositionId);

			$copyPositionId = self::$position1->newPositionFromPosition();
			self::$position2->setId($copyPositionId);

			$posData = self::$position1->selectThis();
			$copyData = self::$position2->selectThis();

			$posData['id'] = null;
			$copyData['id'] = null;

			$this->assertNotEmpty($posData);
			$this->assertNotEmpty($copyData);
			$this->assertEquals($posData, $copyData);
		}

		/**
		 * @depends testAddingPosition
		 */
		public function testChangingPosition(): void
		{
			$pos = ['posX' => 10, 'posY' => 10];
			self::$position2->changeCords($pos['posX'], $pos['posX']);
			$this->assertEquals($pos, self::$position2->selectThis(['posX', 'posY']));
		}

		/**
		 * @depends testAddingPosition
		 */
		public function testChangingMap(): void
		{
			self::$position2->changeMap(self::$position1->getId());

			$this->assertEquals(
				self::$position1->selectThis(['id_map', 'posX', 'posY']),
				self::$position2->selectThis(['id_map', 'posX', 'posY'])
			);
		}
	}

?>
