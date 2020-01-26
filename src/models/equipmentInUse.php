<?php

	namespace SOS
	{
		class EquipmentInUse extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'EQ_HEROES';
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function checkSlot(int $itemId): int
			{
				$item = new Item;
				$item->setId($itemId);
				$typeItem = $item->selectThis(['id_typeitem']);

				$itemsIds = $this->selectArrayThis(['id_item']);
				foreach ($itemsIds as $id)
				{
					$item->setId($id);
					$type = $item->selectThis(['id_typeitem']);

					if ($typeItem == $type)
						return $id;
				}
				return -1;
			}

			public function addItem(int $itemId): int
			{
				$oldItemId = $this->checkSlot($itemId);
				if (!empty($oldItemId))
					$this->removeItem($oldItemId);
				$this->insert([$this->getId(), $itemId]);
				return $oldItemId;
			}

			public function removeItem(int $itemId): void
			{
				$this->where('id = ? AND id_item = ?', [$this->getId(), $itemId]);
				$this->delete();
			}

			public function clear(): void
			{
				$this->deleteThis();
			}
		}
	}

?>
