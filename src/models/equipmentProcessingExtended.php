<?php

	namespace SOS
	{
		class EquipmentProcessingExtended extends EquipmentProcessing
		{
			private $stack = 1;

			static public function getTable(): string
			{
				return '';
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function setStackSize(int $stack): void
			{
				$this->stack = $stack;
			}

			public function addItem(int $index, int $idSubject, $amount = 1): bool
			{
				if ($amount > $this->stack) $amount = $this->stack;
				return parent::addItem($index, $idSubject, $amount);
			}

			public function removeItem(int $index, int $amount = 1): void
			{
				$totalAmount = $this->getItem($index)['amount'] - $amount;
				if ($totalAmount <= 0)
					parent::removeItem($index);
				else $this->changeItemAmount($index, $totalAmount);
			}

			public function changeItemAmount(int $index, int $amount): void
			{
				if ($amount > $this->stack) $amount = $this->stack;
				$this->where('id_owner = ? AND `index` = ?', [$this->getId(), $index]);
				$this->update(['amount' => $amount]);
			}
		}
	}

?>
