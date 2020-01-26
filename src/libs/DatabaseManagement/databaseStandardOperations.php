<?php

	namespace DBManagement
	{
		class DatabaseStandardOperations extends DatabaseConnection
		{
			private $tableName;
			private $whereStorage;
			private $orderByQuery;
			private $limitQuery;

			public function __construct(string $tableName)
			{
				$this->tableName = $tableName;
				parent::__construct();
				$this->clear();
			}

			public function useTable(string $tableName): void
			{
				$this->tableName = $tableName;
			}

			protected function getTableName(): string
			{
				return $this->tableName;
			}

			public function selectArray(array $columns = []): array
			{
				$columnsStr = !empty($columns) ? implode(', ', $columns) : '*';
				$query = 'SELECT '.$columnsStr.' FROM '.$this->tableName;
				$query .= $this->getQueryExtension();

				$db = $this->getConnect()->prepare($query);
				$db->execute($this->whereStorage->get('values'));

				$columnsCount = count($columns);
				if ($columnsStr == '*' || $columnsCount > 1)
					$data = $db->fetchAll();
				else $data = $db->fetchAll(\PDO::FETCH_COLUMN, 0);

				$db->closeCursor();
				$this->clear();
				return $data;
			}

			public function select(array $columns = [])
			{
				$data = $this->selectArray($columns);
				$count = count($data);
				if ($count == 1)
					$data = $data[0];
				return $data;
			}

			public function update(array $array): void
			{
				$template = implode(' = ?, ', array_keys($array)).' = ? ';
				$query = 'UPDATE '.$this->tableName.' SET '.$template;
				$query .= $this->getQueryExtension();

				try
				{
					$this->beginTransaction();
					$db = $this->getConnect()->prepare($query);
					$db->execute(array_merge(array_values($array), $this->whereStorage->get('values')));
					$db->closeCursor();
					$this->commit();
				}
				catch (\PDOException $e)
				{
					$this->rollBack();
					echo '<p>Failed: '.$query.'</p>';
					echo '<p>'.$e->getMessage().'</p>';
				}
				$this->clear();
			}

			public function insert(array $array): int
			{
				$template = rtrim(str_repeat('?,', count($array)), ',');
				$query = 'INSERT INTO '.$this->tableName.' VALUES('.$template.')';

				try
				{
					$this->beginTransaction();
					$db = $this->getConnect()->prepare($query);
					$db->execute($array);
					$id = $this->getConnect()->lastInsertId();
					$db->closeCursor();
					$this->commit();
					return $id;
				}
				catch (\PDOException $e)
				{
					$this->rollBack();
					echo '<p>Failed: '.$query.'</p>';
					echo '<p>'.$e->getMessage().'</p>';
				}
				return -1;
			}

			public function delete(): void
			{
				$query = 'DELETE FROM '.$this->tableName;
				$query .= $this->getQueryExtension();

				try
				{
					$this->beginTransaction();
					$db = $this->getConnect()->prepare($query);
					$db->execute($this->whereStorage->get('values'));
					$db->closeCursor();
					$this->commit();
				}
				catch (\PDOException $e)
				{
					$this->rollBack();
					echo '<p>Failed: '.$query.'</p>';
					echo '<p>'.$e->getMessage().'</p>';
				}
				$this->clear();
			}

			public function where(string $query, array $values = []): void
			{
				$this->whereStorage = new StorageData(['query' => ' WHERE '.$query, 'values' => $values]);
			}

			public function orderBy(string $query): void
			{
				$this->orderByQuery = ' ORDER BY '.$query;
			}

			public function sort(string $query): void
			{
				$this->orderBy($query);
			}

			public function limit($rows, $offset = null): void
			{
				$this->limitQuery = ' LIMIT '.$rows;
				if (!empty($offset)) $this->limitQuery .= ', '.$offset;
			}

			private function getQueryExtension(): string
			{
				return $this->whereStorage->get('query').$this->orderByQuery.$this->limitQuery;
			}

			private function clear(): void
			{
				$this->where('1=1', []);
				$this->orderByQuery = '';
				$this->limitQuery = '';
			}
		}
	}

?>
