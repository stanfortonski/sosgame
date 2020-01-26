<?php

	namespace SOS
	{
		trait TeamReserve
		{
			private $reserveMaxSize = 1;

			public function setReserveMaxSize(int $reserveMaxSize): void
			{
				$this->reserveMaxSize = $reserveMaxSize;
			}

			public function getReserveMaxSize(): int
			{
				return $this->reserveMaxSize * self::RESERVE_MULTIPLIER;
			}

			public function getFromReserve(int $idSubject): array
			{
				$this->useTable('EQ_GENERALS_OF_HEROES');
				$this->where('id = ? AND id_hero = ?', [$this->getId(), $idSubject]);
				$hero = $this->select();
				$this->useDefaultTable();
				return $hero;
			}

			public function getAllReserve(): array
			{
				$this->useTable('EQ_GENERALS_OF_HEROES');
				$heroes = $this->selectThis();
				$this->useDefaultTable();
				return $heroes;
			}

			public function getAmountOfReserve(): int
			{
				$this->useTable('EQ_GENERALS_OF_HEROES');
				$count = $this->selectThis(['COUNT(*)']);
				$this->useDefaultTable();
				return $count;
			}

			public function canAddToReserve(): bool
			{
				return $this->getReserveMaxSize() > $this->getAmountOfReserve();
			}

			public function addToReserve(int $idSubject, bool $isTemp = false): bool
			{
				if ($this->canAddToReserve())
				{
					$this->useTable('EQ_GENERALS_OF_HEROES');
					$this->insert([$this->getId(), $idSubject, $isTemp]);
					$this->useDefaultTable();
					return true;
				}
				return false;
			}

			public function removeFromReserve(int $idSubject): void
			{
				$this->useTable('EQ_GENERALS_OF_HEROES');
				$this->where('id = ? AND id_hero = ?', [$this->getId(), $idSubject]);
				$this->delete();
				$this->useDefaultTable();
			}
		}

		class Team extends EquipmentProcessing
		{
			use TeamReserve;

			private const RESERVE_MULTIPLIER = 9;

      private $heroId;

			static public function getTable(): string
			{
				return 'TEAMS';
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function getSize(): int
			{
				$size = 0;
				$hero = new Hero;
				$character = new Character;

				$teamMates = $this->getAllItems();
				if (!empty($this->heroId))
					$teamMates[] = ['id_subject' => $this->heroId];

				foreach ($teamMates as $teamMate)
				{
					$hero->setId($teamMate['id_subject']);
					$character->setId($hero->selectThis(['id_character']));
					$size += $character->selectThis(['size_in_team']);
				}
				$this->heroId = null;
				return $size;
			}

			public function canAddItem(): bool
			{
				return $this->getMaxSize() >= $this->getSize();
			}

      public function addItem(int $index, int $idSubject, $value = null): bool
      {
				$hero = $this->getFromReserve($idSubject);
				if (!empty($hero))
				{
					$this->heroId = $idSubject;
					parent::addItem($index, $idSubject, $hero['is_temp']);
					$this->removeFromReserve($idSubject);
					return true;
				}
				return false;
      }

			public function removeItem(int $index, int $amount = 1): void
			{
				$teamMate = $this->getItem($index);
				if (!empty($teamMate))
				{
					if ($this->addToReserve($teamMate['id_subject'], $teamMate['is_temp']))
						parent::removeItem($index);
				}
			}

			public function clearEquipment(): void
			{
				parent::clearEquipment();
				$this->useTable('EQ_GENERALS_OF_HEROES');
				$this->deleteThis();
				$this->useDefaultTable();
			}
		}
	}

?>
