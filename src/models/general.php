<?php

	namespace SOS
	{
		class General extends DatabaseOperationsAdapter
		{
			use TimeManagerInterface, GeneralsLists;

			private const LEARN_POINTS_PER_LEVEL = 1;
			private const RESPAWN_SECONDS_PER_LEVEL = 30;

			private $team;
			private $equipment;

			static public function getTable(): string
			{
				return 'GENERALS';
			}

			static public function addGeneral(int $idCharacter, string $name): int
			{
				$idHero = Hero::addHero($idCharacter);

				$character = new Character;
				$character->setId($idCharacter);
				$idOutfit = $character->selectThis(['id_outfit']);

				$presetData = self::getPreset($idCharacter);
				$race = new Race;
				$race->setId($presetData['id_race']);
				$position = new Position;
				$position->setId($race->selectThis(['id_start_position']));
				$idPosition = $position->newPositionFromPosition();

				$idGeneral = static::insertThat([null, $idHero, $idPosition, $idOutfit, $name, 0, $presetData['team_size'], 2, 1, 0]);

				$team = new Team;
				$team->setMaxSize($presetData['team_size']);
				$team->setReserveMaxSize(1);
				$team->setId($idGeneral);
				$team->addToReserve($idHero);
				$team->addItem(1, $idHero);

				return $idGeneral;
			}

			static public function getPresetsCharactersForRace(int $idRace): array
			{
				$db = new static;
				$db->useTable('GENERALS_PRESETS');
				$db->where('id_race = ?', [$idRace]);
				$charactersIds = $db->selectArray(['id_character']);
				return $charactersIds;
			}

			static public function getPreset(int $idCharacter): array
			{
				$db = new static;
				$db->useTable('GENERALS_PRESETS');
				$db->where('id_character = ?', [$idCharacter]);
				$preset = $db->select();
				return $preset;
			}

			static public function getPresets(): array
			{
				$db = new static;
				$db->useTable('GENERALS_PRESETS');
				$presets = $db->selectArray();
				return $presets;
			}

			public function setId(int $id): void
			{
				parent::setId($id);
				$this->team->setId($id);
				$this->equipment->setId($id);
				$arr = $this->selectThis(['team_size', 'eq_max_size', 'max_amount_heroes']);
				if (!empty($arr))
				{
					$this->team->setMaxSize($arr['team_size']);
					$this->team->setReserveMaxSize($arr['max_amount_heroes']);
					$this->equipment->setMaxSize($arr['eq_max_size']);
				}
			}

			public function __construct()
			{
				parent::__construct();
				$this->team = new Team;
				$this->equipment = new Equipment;
			}

			public function getTeamManipulator(): Team
			{
				return $this->team;
			}

			public function getEquipmentManipulator(): Equipment
			{
				return $this->equipment;
			}

			public function getHeroManipulator(): Hero
			{
				$hero = new Hero;
				$hero->setId($this->selectThis(['id_hero']));
				return $hero;
			}

			public function getOutfitManipulator(): Outfit
			{
				$outfit = new Outfit;
				$outfit->setId($this->selectThis(['id_outfit']));
				return $outfit;
			}

			public function getPositionManipulator(): Position
			{
				$position = new Position;
				$position->setId($this->selectThis(['id_position']));
				return $position;
			}

			public function nextLevel(int $expPerLevel): bool
			{
				$hero = $this->getHeroManipulator();
				if ($hero->nextlevel($expPerLevel))
				{
					$lp = $this->selectThis(['learnpoints']);
					$this->update(['learnpoints' => $lp + self::LEARN_POINTS_PER_LEVEL]);
					return true;
				}
				return false;
			}

			public function removeAllGeneralData(): void
			{
				$hero = $this->getHeroManipulator();
				$hero->removeAllHeroData();

				$position = $this->getPositionManipulator();
				$position->deleteThis();

				$this->team->clearEquipment();
				$this->equipment->clearEquipment();

				$this->useTable('FRIENDS_LIST');
				$this->deleteThis();

				$this->useTable('ENEMIES_LIST');
				$this->deleteThis();

				$this->useDefaultTable();
				$this->deleteThis();
			}

			public function kill(): bool
			{
				if (!$this->isDied())
				{
					$seconds = $this->calcSecondsToRespawn();
					$this->useTable('GENERALS_DIES');
					$this->insert([$this->getId(), $this->getTimeAfterFormatAndAddingSeconds($seconds)]);
					$this->useDefaultTable();

					$presetData = self::getPreset($this->getHeroManipulator()->getCharacterManipulator()->getId());
					$race = new Race;
					$race->setId($presetData['id_race']);
					$posId = $race->selectThis(['id_start_position']);
					$this->getPositionManipulator()->changeMap($posId);
					return true;
				}
				return false;
			}

			public function respawn(): bool
			{
				if ($this->isDied() && $this->remainedToRespawnInSeconds() <= 0)
				{
					$this->useTable('GENERALS_DIES');
					$this->deleteThis();
					$this->useDefaultTable();

					$this->getHeroManipulator()->getStatsManipulator()->heal();
					return true;
				}
				return false;
			}

			public function remainedToRespawnInSeconds(): int
			{
				$this->useTable('GENERALS_DIES');
				$endDate = $this->selectThis(['end_date']);
				$this->useDefaultTable();

				if (empty($endDate)) return 0;
				return $this->diffInSeconds($endDate);
			}

			public function isDied(): bool
			{
				$this->useTable('GENERALS_DIES');
				$data = $this->selectThis();
				$this->useDefaultTable();
				return !empty($data);
			}

			private function calcSecondsToRespawn(): int
			{
				$lvl = $this->getHeroManipulator()->selectThis(['lvl']);
				$result = round(pow(1.019, $lvl) * self::RESPAWN_SECONDS_PER_LEVEL)-1;
				if ($result > 600) return 600+$lvl;
				return $result;
			}
		}
	}

?>
