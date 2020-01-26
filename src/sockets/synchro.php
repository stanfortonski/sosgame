<?php

	namespace SOS
	{
		require_once dirname(__FILE__).'/../include.php';
		require_once 'interfaces/basicSocketServer.php';
		require_once 'interfaces/extendedSocketServer.php';
		require_once 'interfaces/sessionSocketServer.php';
		require_once 'extensions/chat.php';
		require_once 'extensions/MapExploration.php';
		require_once 'extensions/relationships.php';

		use Ratchet\MessageComponentInterface;
		use Ratchet\ConnectionInterface;

		class SynchroServer implements MessageComponentInterface
		{
			use BasicSocketServerInterface, ExtendedSocketServerInterface,
				SessionSocketServerInterface, Chat, MapExploration, RelationshipsManagement;

			private $idServer;
			private $clients;
			private $players;
			private $session;
			private $mediator;
			private $queryValue = null;

			public function __construct(int $idServer, string $sessionName = 'SESSION_STORAGE')
			{
				$this->idServer = $idServer;
				$this->clients = new \SplObjectStorage;
				$this->players = [];

				$this->mediator = MainMediator::get();
				$this->session = new \DBManagement\DatabaseOperations($sessionName);
				$this->session->delete();
				echo 'Server '.$idServer.' is ON'."\n";
			}

			public function onOpen(ConnectionInterface $conn)
			{
				$this->clients->attach($conn);
			}

			public function onClose(ConnectionInterface $conn)
			{
				if (!empty($this->players[$conn->resourceId]))
				{
					$this->sendAllRest($conn, 'logouted', ['id' => $this->getGeneralId($conn)]);
					$this->closeSession($conn);
					unset($this->players[$conn->resourceId]);
				}
				$this->clients->detach($conn);
			}

			public function onError(ConnectionInterface $conn, \Exception $e)
			{
				$conn->close();
			}

			public function onMessage(ConnectionInterface $conn, $msg)
			{
				echo $msg."\n";

				$query = json_decode($msg);
				if (empty($query->name)) return;
				else if (empty($query->value))
					$this->queryValue = null;
				else $this->queryValue = $query->value;

				switch ($query->name)
				{
					case 'init':
						$this->initNewPlayer($conn);
					break;

					case 'players':
						$this->getPlayers($conn);
					break;

					case 'mobs':
						$this->getMobs($conn);
					break;

					case 'items':
						$this->getItems($conn);
					break;

					case 'chat':
						$this->chat($conn);
					break;

					case 'chat-with':
						$this->chatWith($conn);
					break;

					case 'move':
						$this->moved($conn);
					break;

					case 'change-map':
						$this->changeMap($conn);
					break;

					case 'general-die':
						$this->generalDied($conn);
					break;

					case 'item-take':
						$this->itemTaken($conn);
					break;

					case 'kill':
						$this->generalDied($conn);
					break;

					case 'respawn':
						$this->generalRespawn($conn);
					break;

					case 'friend-offer':
						$this->makeFriendshipOffer($conn);
					break;

					case 'friend-add':
						$this->addFriend($conn);
					break;

					case 'friend-remove':
						$this->removeFriend($conn);
					break;

					case 'enemies':
						$this->getEnemies($conn);
					break;

					case 'friends':
						$this->getFriends($conn);
					break;
				}
			}

			public function initNewPlayer(ConnectionInterface $conn): void
			{
				$this->closeOldConnections($this->queryValue->playerId);
				$player = new Player;
				$player->setId($this->queryValue->playerId);
				$player->updateLastLoginDate($this->queryValue->generalId);

				$this->players[$conn->resourceId] = [
					'generalId' => $this->queryValue->generalId,
					'playerId' => $this->queryValue->playerId
				];
				$this->initSession($conn);

				$general = $this->mediator->general($this->queryValue->generalId);
				$value = $this->mediator->getDataGeneral();
				if ($general->isDied())
					$this->send($conn, 'died-init', ['time' => $general->remainedToRespawnInSeconds()]);
				else $this->sendAllRest($conn, 'connected', $value);

				$this->send($conn, 'init', $value);
			}
		}
	}

?>
