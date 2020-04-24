<?php

	namespace SOS
	{
		require_once dirname(__FILE__).'/../include.php';
		require_once 'interfaces/basicSocketServer.php';

		use Ratchet\MessageComponentInterface;
		use Ratchet\ConnectionInterface;

		class BattleServer implements MessageComponentInterface
		{
			use BasicSocketServerInterface;

			private $idPlayer;
			private $idGeneral;
			private $idServer;
			private $mediator;
			private $db;
			private $queryValue = null;

			public function __construct(int $idServer)
			{
				$this->idServer = $idServer;
				$this->db = new \DBManagement\DatabaseConnection;
				$this->mediator = MainMediator::get();
			}

			public function onOpen(ConnectionInterface $conn){}
			public function onClose(ConnectionInterface $conn){;}
			public function onError(ConnectionInterface $conn, \Exception $e){$conn->close();}

			public function onMessage(ConnectionInterface $conn, $msg)
			{
				$this->db->refresh();
				echo $msg."\n";

				$query = json_decode($msg);
				if (empty($query->name)) return;
				else if (empty($query->value))
					$this->queryValue = null;
				else
				{
					$this->queryValue = $query->value;
					$this->init();
				}

				switch ($query->name)
				{
					case 'battle-init':{
						echo 'OK';
					}
					break;
				}
			}

			public function init(): void
			{
				$this->idPlayer = $this->queryValue->playerId;
				$this->idGeneral = $this->queryValue->generalId;
				unset($this->queryValue->playerId);
				unset($this->queryValue->generalId);
			}
		}
	}

?>
