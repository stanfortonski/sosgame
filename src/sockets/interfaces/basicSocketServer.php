<?php

	namespace SOS
	{
		use Ratchet\ConnectionInterface;

		trait BasicSocketServerInterface
		{
			public function send(ConnectionInterface $conn, string $name, $value = null): void
			{
				$conn->send(json_encode(['name' => $name, 'value' => $value]));
			}

			public function sendAll(string $name, $value = null): void
			{
				foreach ($this->clients as $client)
				{
					$this->send($client, $name, $value);
				}
			}

			public function sendAllRest(ConnectionInterface $conn, string $name, $value = null): void
			{
				foreach ($this->clients as $client)
				{
					if ($conn != $client)
						$this->send($client, $name, $value);
				}
			}
		}
	}

?>
