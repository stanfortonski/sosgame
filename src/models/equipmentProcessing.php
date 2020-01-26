<?php

	namespace SOS
	{
		class EquipmentProcessing extends DatabaseOperationsAdapter
		{
			private $maxSize;

			static public function getTable(): string
			{
				return '';
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function getAllItems(): array
			{
				$this->orderBy('`index`');
				$this->where('id_owner = ?', [$this->getId()]);
				return $this->selectArray();
			}

			public function getItem(int $index): array
			{
				$this->limit(1);
				$this->where('id_owner = ? AND `index` = ?', [$this->getId(), $index]);
				return $this->select();
			}

			public function getRandomItem(): array
			{
				$this->limit(1);
				$this->orderBy('RAND()');
				$this->where('id_owner = ?', [$this->getId()]);
				return $this->select();
			}

			public function getAmount(): int
			{
				$this->where('id_owner = ?', [$this->getId()]);
				return $this->select(['COUNT(*)']);
			}

			public function getSize(): int
			{
				return $this->getAmount();
			}

			public function getMaxSize(): int
			{
				return $this->maxSize;
			}

			public function setMaxSize(int $size): void
			{
				$this->maxSize = $size;
			}

			public function canAddItem(): bool
			{
				return $this->getMaxSize() > $this->getAmount();
			}

			public function addItem(int $index, int $idSubject, $value = null): bool
			{
				if ($this->canAddItem())
				{
					$values = [$this->getId(), $idSubject, $index];
					if ($value !== null)
						$values[] = $value;
					$this->insert($values);
					return true;
				}
				return false;
			}

			public function removeItem(int $index, int $amount = 1): void
			{
				$this->where('id_owner = ? AND `index` = ?', [$this->getId(), $index]);
				$this->delete();
			}

			private function updateItemIndex(int $oldIndex, int $newIndex): void
			{
				$this->where('id_owner = ? AND `index` = ?', [$this->getId(), $oldIndex]);
				$this->update(['`index`' => $newIndex]);
			}

			public function clearEquipment(): void
			{
				$this->where('id_owner = ?', [$this->getId()]);
				$this->delete();
			}
		}
	}

?>
