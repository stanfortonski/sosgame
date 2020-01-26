<?php

	namespace SOS
	{
		use Ratchet\ConnectionInterface;

		trait ExtendedSocketServerInterface
		{
			public function getGeneralId(ConnectionInterface $conn): int
			{
				return $this->players[$conn->resourceId]['generalId'];
			}

			public function getPlayerId(ConnectionInterface $conn): int
			{
				return $this->players[$conn->resourceId]['playerId'];
			}

			public function sendAllRestInMap(ConnectionInterface $conn, string $name, $value = null): void
			{
				$this->mediator->general($this->getGeneralId($conn));
				$idCurrentMap = $this->mediator->getCurrentMap();

				foreach ($this->clients as $client)
				{
					if ($conn != $client)
					{
						if ($this->mediator->isGeneralOnMap($this->getGeneralId($client), $idCurrentMap))
							$this->send($client, $name, $value);
					}
				}
			}

			public function getAllRestClientsInMap(ConnectionInterface $conn): array
			{
				$arr = [];
				$this->mediator->general($this->getGeneralId($conn));
				$idCurrentMap = $this->mediator->getCurrentMap();

				foreach ($this->clients as $client)
				{
					if ($conn != $client)
					{
						if ($this->mediator->isGeneralOnMap($this->getGeneralId($client), $idCurrentMap))
							$arr[] = $client;
					}
				}
				return $arr;
			}
		}
	}

?>
