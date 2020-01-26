<?php

	namespace DBManagement
	{
		class DatabaseOperations extends DatabaseStandardOperations
		{
			public function __construct($tableName)
			{
				parent::__construct($tableName);
			}

			public function selectArrayById(int $id, array $columns = []): array
			{
				$this->where('id = ?', [$id]);
				return $this->selectArray($columns);
			}

			public function selectById(int $id, array $columns = [])
			{
				$this->where('id = ?', [$id]);
				return $this->select($columns);
			}

			public function updateById(int $id, array $array): void
			{
				$this->where('id = ?', [$id]);
				$this->update($array);
			}

			public function deleteById(int $id): void
			{
				$this->where('id = ?', [$id]);
				$this->delete();
			}

			public function getEnumValue($column)
			{
				$dbname = DatabaseConnection::$database['dbname'];

				$query = 'SELECT SUBSTRING(COLUMN_TYPE, 5) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?';
				$db = $this->getConnect()->prepare($query);
				$db->execute([$dbname, $this->getTableName(), $column]);
				$data = $db->fetch(\PDO::FETCH_COLUMN, 0);
				$db->closeCursor();

				$data = preg_replace('/\'|\(|\)|\'/', '', $data);
				return explode(',', $data);
			}
		}
	}

?>
