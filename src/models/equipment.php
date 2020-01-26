<?php

	namespace SOS
	{
		class Equipment extends EquipmentProcessingExtended
		{
			private const PAGE_SIZE = 36;

			static public function getTable(): string
			{
				return 'EQ_GENERALS_OF_ITEMS';
			}

			public function getMaxSize(): int
			{
				return parent::getMaxSize() * self::PAGE_SIZE;
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function addItem(int $index, int $idSubject, $amount = 1): bool
			{
				$this->setStackOfSubject($idSubject);
				return parent::addItem($index, $idSubject, $amount);
			}

			private function setStackOfSubject(int $idSubject): void
			{
				$item = new Item;
				$item->setId($idSubject);
				$this->setStackSize($item->selectThis(['stack']));
			}
		}
	}

?>
