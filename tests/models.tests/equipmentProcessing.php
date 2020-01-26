<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class EquipmentProcessingTest extends TestCase
	{
		static $eq;

		public function testAddingItemToEquipment(): void
		{
			self::$eq = new SOS\EquipmentProcessing;
			self::$eq->useTable('EQ_PROCESSING');
			self::$eq->setId(1);
			self::$eq->setMaxSize(6);
			self::$eq->clearEquipment();

			$this->assertTrue(self::$eq->canAddItem());

			for ($i = 1; $i <= 6; ++$i)
				self::$eq->addItem($i, 1);

			$this->assertFalse(self::$eq->addItem(7, 2));
			$this->assertEquals(self::$eq->getAmount(), 6);
			$this->assertFalse(self::$eq->canAddItem());
		}

		/**
		 * @depends testAddingItemToEquipment
		 */
		public function testRemovingItemFromEquipment(): void
		{
			$this->assertNotEmpty(self::$eq->getItem(1));
			self::$eq->removeItem(1);
			$this->assertEmpty(self::$eq->getItem(1));
			$this->assertEquals(self::$eq->getAmount(), 5);
		}
	}

?>
