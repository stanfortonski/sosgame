<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class EquipmentProcessingExtendedTest extends TestCase
	{
		static $eq;

		private function clearbeforeStartTests()
		{
			self::$eq = new SOS\EquipmentProcessingExtended;
			self::$eq->useTable('EQ_PROCESSING_EXT');

			self::$eq->setId(1);
			self::$eq->setMaxSize(10);
			self::$eq->setStackSize(5);
			self::$eq->clearEquipment();
		}

		public function testAddingItemToComplexEquipment(): void
		{
			$this->clearbeforeStartTests();
			self::$eq->addItem(1, 1);
			$item = self::$eq->getItem(1);
			$this->assertEquals($item['amount'], 1);

			self::$eq->addItem(2, 2, 10);
			$item = self::$eq->getItem(2);
			$this->assertEquals($item['amount'], 5);

			self::$eq->addItem(3, 1, 3);
			$item = self::$eq->getItem(3);
			$this->assertEquals($item['amount'], 3);
		}

		/**
     * @depends testAddingItemToComplexEquipment
     */
		public function testChangingAmountOfItemInSlot(): void
		{
			self::$eq->changeItemAmount(1, 4);
			$item = self::$eq->getItem(1);
			$this->assertEquals($item['amount'], 4);

			self::$eq->changeItemAmount(2, 10);
			$item = self::$eq->getItem(2);
			$this->assertEquals($item['amount'], 5);

			self::$eq->removeItem(3, 2);
			$item = self::$eq->getItem(3);
			$this->assertEquals($item['amount'], 1);

			self::$eq->removeItem(3);
			$item = self::$eq->getItem(3);
			$this->assertEmpty($item);
		}
	}

?>
