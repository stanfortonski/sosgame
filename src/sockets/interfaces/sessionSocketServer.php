<?php

	namespace SOS
	{
		use Ratchet\ConnectionInterface;

		trait SessionSocketServerInterface
		{
			public function initSession(ConnectionInterface $conn): void
			{
				$this->session->insert([$conn->resourceId, $this->players[$conn->resourceId]['playerId']]);
			}

			public function closeSession(ConnectionInterface $conn): void
			{
				$this->session->where('id_connection = ? and id_player = ?', [$conn->resourceId, $this->players[$conn->resourceId]['playerId']]);
				$this->session->delete();
			}

			public function closeOldConnections(int $playerId): void
			{
				$this->session->where('id_player = ?', [$playerId]);
				$connections = $this->session->selectArray(['id_connection']);

				foreach ($connections as $connection)
				{
					foreach ($this->clients as $client)
					{
						if ($client->resourceId == $connection)
						{
							$client->close();
							break;
						}
					}
				}
			}
		}
	}

?>
