<?php

	namespace SOS
	{
		class Mob extends DatabaseOperationsAdapter
		{
			use TimeManagerInterface;

			private const RESPAWN_MULTIPIER = 60;

			private $idServer;
			private $idGroup;

			static public function getTable(): string
			{
				return 'MOBS';
			}

			static public function getTypesOfMobs(): array
			{
				$db = new static;
				$db->useTable('TYPES_OF_MOBS');
				return $db->selectArray();
			}

			static public function respawnMobs(): void
			{
				$db = new self;
				$db->useTable('MOBS_DIES');
				$db->where('end_date <= NOW()');
				$db->delete();
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function setServerId(int $id): void
			{
				$this->idServer = $id;
			}

			public function getServerId(): int
			{
				return $this->idServer;
			}

			public function setGroupId(int $id): void
			{
				$this->idGroup = $id;
			}

			public function getGroupId(): int
			{
				return $this->idGroup;
			}

			public function getHeroManipulator()
			{
				$hero = new Hero;
				$hero->setId($this->selectThis(['id_hero']));
				return $hero;
			}

			public function kill(int $idGeneral): bool
			{
				if (!$this->isDied())
				{
					$seconds = $this->getTimeAfterFormatAndAddingSeconds($this->calcSecondsToRespawn());
					$this->useTable('MOBS_DIES');
					$this->insert([$this->idServer, $this->idGroup, $this->getId(), $idGeneral, $seconds]);
					$this->useDefaultTable();
					$this->getHeroManipulator()->getStatsManipulator()->heal();
					return true;
				}
				return false;
			}

			public function isDied(): bool
			{
				$this->useTable('MOBS_DIES');
				$this->where('id_server = ? AND id_group = ? AND id_mob = ?', [$this->idServer, $this->idGroup, $this->getId()]);
				$data = $this->select();
				$this->useDefaultTable();
				return !empty($data);
			}

			public function calcSecondsToRespawn(): int
			{
				$lvl = (int)$this->getHeroManipulator()->selectThis(['lvl']);
				if ($lvl <= 10) $lvl = 1;
				else $lvl -= 10;

				return round((log10($lvl)+1) * self::RESPAWN_MULTIPIER);
			}

			public function getRandomizeItems(): array
			{
				$awards = [];
				$drops = $this->getAllDrops();
				if (!empty($drops))
				{
					foreach ($drops as $drop)
					{
						$chance = rand(1, $drop['difficult']);
						if ($chance == $drop['difficult'])
						{
							$drop['amount'] = rand(1, $drop['amount']);
							$awards[] = $drop;
						}
					}
					shuffle($awards);
				}
				return $awards;
			}

			private function getAllDrops(): array
			{
				$this->useTable('DROPS_FROM_MOBS');
				$this->where('id_mob = ?', [$this->getId()]);
				$drops = $this->selectArray();
				$this->useDefaultTable();
				return $drops;
			}
		}
	}

?>
