<?php
	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class ItemTest extends TestCase
	{
		static $item;

		private function clearDataBeforeStartTests(): void
		{
			self::$item = new SOS\Item;
			self::$item->useTable('POSITIONS_OF_DROPED_ITEMS');
			self::$item->where('id_server = ? AND id_position = ? AND id_item = ?', [1, 3, 1]);
			self::$item->delete();
			self::$item->insert([1, 3, 1, 1]);
			self::$item->useDefaultTable();
		}

		public function testTakingItemsFromGround(): void
		{
			$this->clearDataBeforeStartTests();
			$takenItems = new DBManagement\DatabaseOperations('TAKEN_ITEMS');

			$probably = [
				['id_position' => 1, 'id_item' => 1, 'amount' => 1],
				['id_position' => 1, 'id_item' => 2, 'amount' => 1],
				['id_position' => 3, 'id_item' => 1, 'amount' => 1]
			 ];
			$items = SOS\Item::getAllItemsOnEveryGround(1);

			$this->assertEquals(count($items), count($probably));
			$this->assertEquals($items, $probably);
			$this->assertEquals(SOS\Item::getAllItemsOnEveryGround(2)[2], ['id_position' => 4, 'id_item' => 2, 'amount' => 1]);

			self::$item->setId(1);
			self::$item->takeItemFromGround(1, 3);
			$items = SOS\Item::getAllItemsOnEveryGround(1);
			$this->assertEquals(count($items), count($probably)-1);

			self::$item->takeItemFromGround(1, 1);
			$this->assertEquals(count($takenItems->selectArray()), 1);

			self::$item->respawnItems();
			$this->assertEquals(count($takenItems->selectArray()), 1);

			$takenItems->update(['end_date' => date('Y-m-d H:i:s')]);

			self::$item->respawnItems();
			$this->assertEquals(count($takenItems->selectArray()), 0);
		}
	}

?>
