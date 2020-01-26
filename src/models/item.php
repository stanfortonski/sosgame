<?php

	namespace SOS
	{
		class Item extends DatabaseOperationsAdapter
		{
			use TimeManagerInterface;

			private const RESP_ITEM_TIME_IN_SEC = 120;

			static public function getTable(): string
			{
				return 'ITEMS';
			}

			static public function getPath(): string
			{
				return Config::get()->path('game').'imgs/items/';
			}

			static public function getRanks(): array
			{
				$db = new static;
				$db->useTable('RANKS_OF_ITEMS');
				return $db->selectArray();
			}

			static public function getTypes(): array
			{
				$db = new static;
				$db->useTable('TYPES_OF_ITEMS');
				return $db->selectArray();
			}

			static public function respawnItems(): void
			{
				$db = new self;
				$db->useTable('TAKEN_ITEMS');
				$db->where('end_date <= NOW()');
				$db->delete();
			}

			static public function getAllItemsOnEveryGround(int $idServer): array
			{
				self::respawnItems();
				return array_merge(self::getAllDefaultItemsOnEveryGround(), self::getAllDroppedItemsOnEveryGround($idServer));
			}

			static private function getAllDefaultItemsOnEveryGround(): array
			{
				$db = new self;
				$db->useTable('POSITIONS_OF_DEFAULT_ITEMS as t1 LEFT JOIN TAKEN_ITEMS as t2 ON t1.id_position = t2.id_position AND t1.id_item = t2.id_item');
				$db->where('t2.id_position IS NULL');
				return $db->selectArray(['t1.id_position', 't1.id_item', 't1.amount']);
			}

			static private function getAllDroppedItemsOnEveryGround(int $idServer): array
			{
				$db = new self;
				$db->useTable('POSITIONS_OF_DROPED_ITEMS');
				$db->where('id_server = ?', [$idServer]);
				return $db->selectArray(['id_position', 'id_item', 'amount']);
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function getIconPath(): string
			{
				return static::getPath().$this->selectThis(['icon']);
			}

			public function getStatsManipulator(): Stats
			{
				$stats = new Stats;
				$stats->setId($this->selectThis(['id_stats']));
				return $stats;
			}

			public function takeItemFromGround(int $idServer, int $idPosition): void
			{
				if (!$this->takeDefaultItemFromGround($idServer, $idPosition))
					$this->takeDroppedItemFromGround($idServer, $idPosition);
			}

			private function takeDefaultItemFromGround(int $idServer, int $idPosition): bool
			{
				$result = false;
				$this->useTable('POSITIONS_OF_DEFAULT_ITEMS');
				$this->where('id_item = ? AND id_position = ?', [$this->getId(), $idPosition]);
				if (!empty($this->select()))
				{
					$seconds = $this->getTimeAfterFormatAndAddingSeconds(self::RESP_ITEM_TIME_IN_SEC);

					$this->useTable('TAKEN_ITEMS');
					$this->insert([$idServer, $idPosition, $this->getId(), $seconds]);
					$result = true;
				}
				$this->useDefaultTable();
				return $result;
			}

			private function takeDroppedItemFromGround(int $idServer, int $idPosition): void
			{
				$this->useTable('POSITIONS_OF_DROPED_ITEMS');
				$this->where('id_server = ? AND id_item = ? AND id_position = ?', [$idServer, $this->getId(), $idPosition]);
				$this->delete();
				$this->useDefaultTable();
			}
		}
	}

?>
