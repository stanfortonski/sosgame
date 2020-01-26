<?php

	namespace SOS
	{
		use Ratchet\ConnectionInterface;

		trait Chat
		{
			public function chat(ConnectionInterface $conn): void
			{
				$message = $this->getMessage($conn);
				$this->send($conn, 'chat', $message);
				$this->sendAllRestInMap($conn, 'chat', $message);
			}

			public function chatWith(ConnectionInterface $conn): void
			{
				$curName = strtolower($this->mediator->general($this->getGeneralId($conn))->selectThis(['name']));
				if ($curName == $this->queryValue->name)
					return;

				foreach ($this->clients as $client)
				{
					$name = strtolower($this->mediator->general($this->getGeneralId($client))->selectThis(['name']));
					if ($name == $this->queryValue->name)
					{
						$message = $this->getMessage($conn, $this->queryValue);
						$this->send($conn, 'chat', $message);
						$this->send($client, 'chat', $message);
						return;
					}
				}
				$this->send($conn, 'chat-error', [$this->queryValue->name]);
			}

			private function getMessage(ConnectionInterface $conn): string
			{
				$name = $this->mediator->general($this->getGeneralId($conn))->selectThis(['name']);
				$pw = empty($this->queryValue->name) ? '' : '[PM] ';
				return $pw.'<span class="name">'.$name.'</span>: '.htmlspecialchars($this->queryValue->content);
			}
		}
	}

?>
