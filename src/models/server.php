<?php

	namespace SOS
	{
		class Server extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'SERVERS';
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function isNameAvailable(string $name): bool
			{
				$this->useTable('SERVERS as s, PLAYERS as p, GENERALS as g');

				$this->where(
					'p.id_general = g.id AND p.id_server = s.id AND s.id = ? AND LOWER(g.name) = ?',
					[$this->getId(), strtolower($name)]
				);
				$amount = $this->select(['COUNT(*)']);

				$this->useDefaultTable();
				return $amount == 0;
			}

			public function getExpPerLevel(): int
			{
				return $this->selectThis(['exp_multiplier']);
			}

			public function getMaxAmountOfGenerals(): int
			{
				return $this->selectThis(['max_generals']);
			}
		}
	}

?>
