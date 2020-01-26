<?php

	namespace SOS
	{
		use \DBManagement as DB;

		abstract class DatabaseOperationsAdapter extends DB\DatabaseOperations
		{
			private $id = 0;

			public function __construct()
			{
				parent::__construct(static::getTable());
			}

			public function getId(): int
			{
				return $this->id;
			}

			public function setId(int $id): void
			{
				$this->id = $id;
			}

			public function useDefaultTable(): void
			{
				$this->useTable(static::getTable());
			}

			public function selectThis(array $columns = [])
			{
				return $this->selectById($this->getId(), $columns);
			}

			public function selectArrayThis(array $columns = []): array
			{
				return $this->selectArrayById($this->getId(), $columns);
			}

			public function updateThis(array $data): void
			{
				$this->updateById($this->getId(), $data);
			}

			public function deleteThis(): void
			{
				$this->deleteById($this->getId());
			}

			static public function insertThat(array $data): int
			{
				$db = new static;
				$id = $db->insert($data);
				return $id;
			}

			abstract static public function getTable(): string;
		}
	}

?>
