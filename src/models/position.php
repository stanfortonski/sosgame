<?php

	namespace SOS
	{
		class Position extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'POSITIONS';
			}

			static public function addPosition($idMap, $x, $y): int
			{
				return static::insertThat([null, $idMap, $x, $y]);
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function newPositionFromPosition(): int
			{
				$posData = $this->selectThis();
				$posData['id'] = null;
				return $this->insert(array_values($posData));
			}

			public function change(int $idMap, int $x = 1, int $y = 1)
			{
				$this->updateThis(['id_map' => $idMap, 'posX' => $x, 'posY' => $y]);
			}

			public function changeMap(int $idPosition): void
			{
				$idOld = $this->getId();

				$this->setId($idPosition);
				$posData = $this->selectThis();

				$this->setId($idOld);
				$this->updateThis(['id_map' => $posData['id_map'], 'posY' => $posData['posY'], 'posX' => $posData['posX']]);
			}

			public function changeCords(int $x, int $y): void
			{
				$this->updateThis(['posX' => $x, 'posY' => $y]);
			}
		}
	}

?>
