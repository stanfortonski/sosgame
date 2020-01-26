<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;
	use DBManagement as DB;

	class DatabaseOperationsTest extends TestCase
	{
		const TABLE_NAME = 'test';

		public function testSelectingData(): void
		{
			$rowId = 1;
			$dataExpected = ['id' => $rowId, 'name' => 'TEST UNIT', 'bool' => false, 'value' => 666];

			$db = new DB\DatabaseOperations(self::TABLE_NAME);
			$dataFromDb = $db->selectById($rowId);
			$this->assertEquals($dataExpected, $dataFromDb);

			$db->where('name = ?', [$dataExpected['name']]);
			$dataFromDb = $db->select();
			$this->assertEquals($dataExpected, $dataFromDb);

			$selectedName = $db->selectById($rowId, ['name']);
			$this->assertEquals($selectedName, $dataExpected['name']);
		}

		public function testSortingSelectedData(): void
		{
			$lastId = 6;
			$firstName = 'a';

			$db = new DB\DatabaseOperations(self::TABLE_NAME);
			$db->sort('id DESC');
			$dataDescSorted = $db->select();

			$db->sort('name ASC');
			$dataASCSorted = $db->select();

			$this->assertEquals($dataDescSorted[0]['id'], $lastId);
			$this->assertEquals($dataASCSorted[0]['name'], $firstName);
		}

		public function testLimitInSelectCase(): void
		{
			$rowCount = 2;
			$limitFirstId = 5;
			$limitLastId = 6;

			$db = new DB\DatabaseOperations(self::TABLE_NAME);
			$db->limit($rowCount);
			$two = $db->select();

			$db->limit(4, 2);
			$dataFromDb = $db->select();
			$firstId = $dataFromDb[0]['id'];
			$lastId = $dataFromDb[1]['id'];

			$this->assertEquals(count($two), $rowCount);
			$this->assertEquals($firstId, $limitFirstId);
			$this->assertEquals($lastId, $limitLastId);
		}

		public function testGettingInformationAboutEnum(): void
		{
			$db = new DB\DatabaseOperations('test_enum');
			$orginalData = ['good', 'bad', 'neutral'];
			$enumFromDb = $db->getEnumValue('enumerator');

			$this->assertEquals($enumFromDb, $orginalData);
		}

		public function testInsertingData(): void
		{
			$insertedData = ['name' => 'TEST NAME', 'bool' => true, 'value' => 199];

			$db = new DB\DatabaseOperations(self::TABLE_NAME);
			$insertedData['id'] = $db->insert([null, $insertedData['name'], $insertedData['bool'], $insertedData['value']]);

			$selectedData = $this->select($insertedData['id'], 'fetch');
			$this->assertEquals($selectedData, $insertedData);

			$this->delete($insertedData['id']);
		}

		public function testDeletingData(): void
		{
			$rowId = 1000;

			$pdo = (new DB\DatabaseConnection)->getConnect();
			$operation = $pdo->prepare('INSERT INTO '.self::TABLE_NAME.' values(?, "", 1, 1)');
			$operation->execute([$rowId]);
			$operation->closeCursor();

			$oldCount = $this->select($rowId, 'count');

			$db = new DB\DatabaseOperations('test');
			$db->deleteById($rowId);

			$newCount = $this->select($rowId, 'count');

			$this->assertEquals($newCount, 0);
			$this->assertTrue($oldCount > $newCount);

			$this->delete($rowId);
		}

		public function testUpdatingData(): void
		{
			$rowId = 2;
			define('OLD_NAME', 'JANUSZ');
			define('NEW_NAME', 'ZENEK');

			$pdo = (new DB\DatabaseConnection)->getConnect();
			$operation = $pdo->prepare('UPDATE '.self::TABLE_NAME.' set name = ? WHERE id = ?');
			$operation->execute([OLD_NAME, $rowId]);
			$operation->closeCursor();

			$oldName = $this->select($rowId, 'fetch')['name'];

			$db = new DB\DatabaseOperations(self::TABLE_NAME);
			$db->updateById($rowId, ['name' => NEW_NAME]);
			$newName = $this->select($rowId, 'fetch')['name'];

			$this->assertEquals($oldName, OLD_NAME);
			$this->assertEquals($newName, NEW_NAME);
		}

		private function delete(int $id): void
		{
			$pdo = (new DB\DatabaseConnection)->getConnect();
			$operation = $pdo->prepare('DELETE FROM '.self::TABLE_NAME.' WHERE id = ?');
			$operation->execute([$id]);
			$operation->closeCursor();
		}

		private function select(int $id, string $str)
		{
			$pdo = (new DB\DatabaseConnection)->getConnect();
			$operation = $pdo->prepare('SELECT * FROM '.self::TABLE_NAME.' WHERE id = ?');
			$operation->execute([$id]);
			if ($str === 'fetch')
				$result = $operation->fetch();
			else if ($str === 'count')
				$result = $operation->rowCount();
			$operation->closeCursor();
			return $result;
		}
	}

?>
