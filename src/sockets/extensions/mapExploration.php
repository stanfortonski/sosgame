<?php

	namespace SOS
	{
		use Ratchet\ConnectionInterface;

		trait MapExploration
		{
			public function getPlayers(ConnectionInterface $conn): void
			{
				$values = [];
				foreach ($this->getAllRestClientsInMap($conn) as $client)
				{
					$this->mediator->general($this->getGeneralId($client));
					$values[] = $this->mediator->getDataGeneral();
				}
				$this->send($conn, 'players', $values);
			}

			public function getMobs(ConnectionInterface $conn): void
			{
				$this->mediator->general($this->getGeneralId($conn));
				$idCurrentMap = $this->mediator->getCurrentMap();
				$this->send($conn, 'mobs', $this->mediator->getDataMobsOnMap($this->idServer, $idCurrentMap));
			}

			public function getItems(ConnectionInterface $conn): void
			{
				$this->mediator->general($this->getGeneralId($conn));
				$idCurrentMap = $this->mediator->getCurrentMap();
				$this->send($conn, 'items', $this->mediator->getDataItemsOnMap($this->idServer, $idCurrentMap));
			}

			public function moved(ConnectionInterface $conn): void
			{
				$position = $this->mediator->general($this->getGeneralId($conn))->getPositionManipulator();
				$position->changeCords($this->queryValue->posX, $this->queryValue->posY);
				$this->sendAllRestInMap($conn, 'moved', array_merge(['id' => $this->getGeneralId($conn)], (array) $this->queryValue));
			}

			public function changeMap(ConnectionInterface $conn): void
			{
				$position = $this->mediator->general($this->getGeneralId($conn))->getPositionManipulator();
				$position->changeMap($this->queryValue->position);
				$this->send($conn, 'change-map');
			}

			public function itemTaken(ConnectionInterface $conn): void
			{
				$this->mediator->takeItem($this->queryValue->id, $this->idServer, $this->queryValue->posid);
				$this->sendAllRestInMap($conn, 'item-taken', (array) $this->queryValue);
			}

			public function generalDied(ConnectionInterface $conn): void
			{
				$generalId = $this->getGeneralId($conn);
				$this->mediator->general($generalId)->kill();
				$this->send($conn, 'died', ['time' => $this->mediator->general()->remainedToRespawnInSeconds()]);
				$this->sendAllRest($conn, 'logouted', ['id' => $generalId]);
			}

			public function generalRespawn(ConnectionInterface $conn): void
			{
				$this->mediator->general($this->getGeneralId($conn))->respawn();
				$this->send($conn, 'change-map');
			}
		}
	}

?>
