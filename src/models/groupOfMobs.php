<?php

	namespace SOS
	{
		class GroupOfMobs extends DatabaseOperationsAdapter
		{
			use TimeManagerInterface;

			private const MAX_GROUP_SIZE = 6;
			private const MAX_DROP_SIZE_PER_MOB = 2;

			private $mob;

			static public function getTable(): string
			{
				return 'GROUPS_OF_MOBS';
			}

			static public function respawnGroups(): void
			{
				$db = new self;
				$db->useTable('GROUP_OF_MOBS_ELIMINATED');
				$db->where('end_date <= NOW()');
				$db->delete();
			}

			static public function getAliveGroups(int $idServer): array
			{
				self::respawnGroups();
				$db = new self;
				$db->useTable('GROUPS_OF_MOBS AS t1 LEFT JOIN GROUP_OF_MOBS_ELIMINATED AS t2 ON t1.id = t2.id_group');
				$db->where('t2.id_server IS NULL OR t2.id_server != ?', [$idServer]);
				return $db->selectArray(['t1.id as id', 't1.id_position as id_position', 't1.relations as relations']);
			}

			public function __construct()
			{
				parent::__construct();
				$this->mob = new Mob;
			}

			public function setId(int $id): void
			{
				parent::setId($id);
				$this->mob->setGroupId($id);
			}

			public function setServerId(int $id): void
			{
				$this->mob->setServerId($id);
			}

			public function getServerId(): int
			{
				return $this->mob->getServerId();
			}

			public function getAliveMobs(): array
			{
				Mob::respawnMobs();
				$this->useTable('MOBS_IN_GROUPS AS t1 LEFT JOIN MOBS_DIES AS t2 ON t1.id_group = t2.id_group AND t1.id_mob = t2.id_mob');
				$this->where('t2.id_server IS NULL OR t2.id_server != ? AND t1.id_group = ?', [$this->getServerId(), $this->getId()]);
				$this->orderBy('t1.index');
				$mobs = $this->selectArray(['t1.id_mob as id', 't1.index as `index`']);
				$this->useDefaultTable();
				return $mobs;
			}

			public function getMobs(): array
			{
				$this->useTable('MOBS_IN_GROUPS');
				$this->where('id_group = ?', [$this->getId()]);
				$mobs = $this->selectArray();
				$this->useDefaultTable();
				return $mobs;
			}

			public function getSizeOfGroup(): int
			{
				$this->useTable('MOBS_IN_GROUPS');
				$this->where('id_group = ?', [$this->getId()]);
				$count = $this->select(['COUNT(*)']);
				$this->useDefaultTable();
				return $count;
			}

			public function eliminate(): void
			{
				if (!$this->isEliminate())
				{
					$this->mob->setId($this->getHighestLevelMobId());
					$seconds = $this->getTimeAfterFormatAndAddingSeconds($this->mob->calcSecondsToRespawn());
					$this->useTable('GROUP_OF_MOBS_ELIMINATED');
					$this->insert([$this->getServerId(), $this->getId(), $seconds]);
					$this->useDefaultTable();
				}
			}

			public function isEliminate(): bool
			{
				$this->useTable('GROUP_OF_MOBS_ELIMINATED');
				$this->where('id_server = ? AND id_group = ?', [$this->getServerId(), $this->getId()]);
				$data = $this->select();
				$this->useDefaultTable();
				return !empty($data);
			}

			public function getWholeDrop(int $idGeneral): array
			{
				$mobs = $this->getDefeatedMobsByGeneral($idGeneral);
				$drop = [];
				foreach ($mobs as $id)
				{
					$this->mob->setId($id);
					$items = $this->mob->getRandomizeItems();
					if (!empty($items))
					{
						$drop[] = $items[0];
						if (count($items) >= self::MAX_DROP_SIZE_PER_MOB)
							$drop[] = $items[1];
					}
				}
				return $drop;
			}

			private function getDefeatedMobsByGeneral(int $idGeneral): array
			{
				$this->useTable('MOBS_DIES');
				$this->where('id_won_general = ? AND M.id_server = ? AND M.id_group = ?', [$idGeneral, $this->getServerId(), $this->getId()]);
				$mobs = $this->selectArray(['id_mob']);
				$this->useDefaultTable();
				return $mobs;
			}

			private function getHighestLevelMobId(): int
			{
				$maxLvl = -1;
				$generalId = -1;
				$mob = new Mob;

				foreach ($this->getMobs() as $mob)
				{
					$id = $mob['id_mob'];
					$this->mob->setId($id);
					$lvl = $this->mob->getHeroManipulator()->selectThis(['lvl']);
					if ($maxLvl < $lvl)
					{
						$generalId = $id;
						$maxLvl = $lvl;
					}
				}
				return $generalId;
			}
		}
	}

?>
