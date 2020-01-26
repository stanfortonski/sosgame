<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class EqInUseTest extends TestCase
	{
		public function testAddingItemToUse(): void
		{
			$eq = new SOS\EquipmentInUse;
			$eq->setId(1);
			$eq->clear();

			$this->assertEquals($eq->checkSlot(1), -1);

			$eq->addItem(1);
			$this->assertNotEquals($eq->checkSlot(1), -1);

			$this->assertEquals($eq->addItem(2), 1);
			$this->assertEquals($eq->checkSlot(2), 2);

			$this->assertEquals($eq->checkSlot(1), $eq->checkSlot(2));
			$this->assertEquals($eq->selectThis(['COUNT(*)']), 1);
		}
	}

?>
