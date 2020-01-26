<?php

	namespace SOS
	{
		class Player extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'PLAYERS';
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function getUserId(): int
			{
				return $this->getId();
			}

			public function setUserId(int $id): void
			{
				$this->setId($id);
			}

			public function updateLastLoginDate(int $generalId): void
			{
				$this->where('id = ? AND id_general = ?', [$this->getId(), $generalId]);
				$this->update(['last_login_date' => date('Y-m-d H:i:s')]);
			}

			public function addGeneral(int $idServer, int $idCharacter, string $name): bool
			{
				if ($this->canAddGeneral($idServer, $name))
				{
					$idGeneral = General::addGeneral($idCharacter, $name);
					$this->insert([$this->getId(), $idServer, $idGeneral, '']);
					return true;
				}
				return false;
			}

			public function canAddGeneral(int $idServer, $name = null): bool
			{
				$server = new Server;
				$server->setId($idServer);

				$isAvailable = $name == null ? true : $server->isNameAvailable($name);
				$maxAmount = $server->getMaxAmountOfGenerals();
				$amount = $this->getAmountOfGeneralsAtServer($idServer);

				return $isAvailable & ($maxAmount > $amount);
			}

			public function getGeneralManipulator(int $idGeneral)
			{
				$general = new General;
				$general->setId($idGeneral);
				if ($this->hasGeneral($idGeneral))
					return $general;
				return false;
			}

			public function hasGeneral(int $idGeneral): bool
			{
				$this->where('id = ? AND id_general = ?', [$this->getId(), $idGeneral]);
				return !empty($this->select());
			}

			public function changeGeneralName(int $idGeneral, string $name): bool
			{
				$general = $this->getGeneralManipulator($idGeneral);
				if (!$general)
					return false;
				if ($general->selectThis(['name']) == $name)
					return false;

				$server = new Server;
				$this->where('id = ? AND id_general = ?', [$this->getId(), $idGeneral]);
				$server->setId($this->select(['id_server']));
				if ($server->isNameAvailable($name))
				{
					$general->updateThis(['name' => $name]);
					return true;
				}
				return false;
			}

			public function getServersWithGenerals(): array
			{
				$server = new Server;
				$count = $server->select(['COUNT(*)']);

				$serversWithGenerals = [];
				for ($i = 1; $i <= $count; ++$i)
				{
					$generals = $this->getGeneralsIdsAtServer($i);
					if (!empty($generals))
						$serversWithGenerals[] = [$i, $generals];
				}
				return $serversWithGenerals;
			}

			public function getGeneralsIdsAtServer(int $idServer): array
			{
				$this->where('id = ? AND id_server = ?', [$this->getId(), $idServer]);
				return $this->selectArray(['id_general']);
			}

			public function getAllGeneralsIds(): array
			{
				return $this->selectArrayThis(['id_general']);
			}

			public function getAmountOfGenerals(): int
			{
				return $this->selectThis(['COUNT(*)']);
			}

			public function getAmountOfGeneralsAtServer(int $idServer): int
			{
				$this->where('id = ? AND id_server = ?', [$this->getId(), $idServer]);
				return $this->select(['COUNT(*)']);
			}

			public function removeOncePlayerData(int $idGeneral): void
			{
				$general = $this->getGeneralManipulator($idGeneral);
				$general->removeAllGeneralData();

				$this->where('id = ? AND id_general = ?', [$this->getId(), $idGeneral]);
				$this->delete();
			}

			public function removeAllPlayerData(): void
			{
				$general = new General;

				$generalsIds = $this->getAllGeneralsIds();
				foreach ($generalsIds as $id)
				{
					$general->setId($id);
					$general->removeAllGeneralData();
				}
				$this->deleteThis();
			}

			public function deleteAfterYouDidNotPlayForFifteenDays(int $idGeneral): bool
			{
				$this->where('id = ? AND id_general = ?', [$this->getId(), $idGeneral]);
				$player = $this->select();

				if (empty($player))
					return false;
				else if (empty($player['last_login_date']))
				{
					$this->removeOncePlayerData($idGeneral);
					return true;
				}
				else
				{
					$lastLoginDate = new \DateTime($player['last_login_date']);
					$now = new \DateTime;
					$diff = $now->diff($lastLoginDate);
					if ($diff->days >= 15)
					{
						$this->removeOncePlayerData($idGeneral);
						return true;
					}
				}
				return false;
			}
		}
	}

?>
