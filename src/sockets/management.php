<?php

	namespace SOS
	{
		require_once dirname(__FILE__).'/../include.php';
		require_once 'interfaces/basicSocketServer.php';
		require_once 'extensions/relationships.php';

		use Ratchet\MessageComponentInterface;
		use Ratchet\ConnectionInterface;

		class ManagementServer implements MessageComponentInterface
		{
			use BasicSocketServerInterface, RelationshipsManagement;

			private $idPlayer;
			private $idGeneral;
			private $idServer;
			private $user;
			private $mediator;
			private $queryValue = null;

			public function __construct(int $idServer)
			{
				$this->idServer = $idServer;
				$this->user = new User;
				$this->mediator = MainMediator::get();
			}

			public function onOpen(ConnectionInterface $conn){}
			public function onClose(ConnectionInterface $conn){}
			public function onError(ConnectionInterface $conn, \Exception $e){$conn->close();}

			public function onMessage(ConnectionInterface $conn, $msg)
			{
				$this->user->refresh();

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
					case 'options-get':
						$this->getOptions($conn);
					break;

					case 'options-set':
						$this->setOptions($conn);
					break;

					case 'team':
						$this->getTeam($conn);
					break;

					case 'stats-get':
						$this->getStats($conn);
					break;

					case 'enemy-add':
						$this->addEnemy($conn);
					break;

					case 'enemy-remove':
						$this->removeEnemy($conn);
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

			public function setOptions(ConnectionInterface $conn): void
			{
				$this->user->setId($this->idPlayer);
				$options = $this->user->getOptionsManipulator();
				$options->updateThis((array) $this->queryValue);
			}

			public function getOptions(ConnectionInterface $conn): void
			{
				$this->user->setId($this->idPlayer);
				$options = $this->user->getOptionsManipulator();
				$this->send($conn, 'options-get', $options->selectThis());
			}

			public function getTeam(ConnectionInterface $conn): void
			{
				$this->mediator->general($this->idGeneral);
				$this->send($conn, 'team', $this->mediator->getDataTeam());
			}

			public function getStats(ConnectionInterface $conn): void
			{
				$stats = new Stats;
				$stats->setId($this->queryValue->id_stats);
				$this->send($conn, 'stats-get', $stats->selectThis());
			}
		}
	}

?>
