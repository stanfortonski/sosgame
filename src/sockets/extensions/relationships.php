<?php

	namespace SOS
	{
		use Ratchet\ConnectionInterface;

		trait RelationshipsManagement
		{
			public function makeFriendshipOffer(ConnectionInterface $conn): void
			{
				$id = $this->getGeneralId($conn);
				foreach($this->clients as $client)
				{
					if ($this->getGeneralId($client) == $this->queryValue->idTarget)
						$this->send($client, 'friend-offer', ['id' => $id, 'name' => $this->mediator->general($id)->selectThis(['name'])]);
				}
			}

			public function addFriend(ConnectionInterface $conn): void
			{
				$id = $this->getGeneralId($conn);
				foreach($this->clients as $client)
				{
					if ($this->getGeneralId($client) == $this->queryValue->idOffered)
					{
						$this->mediator->general($id)->addFriend($this->queryValue->idOffered);
						$this->mediator->general()->removeEnemy($this->queryValue->idOffered);
						$this->send($client, 'friend-add', ['id' => $id]);

						$this->mediator->general($this->queryValue->idOffered)->addFriend($id);
						$this->mediator->general()->removeEnemy($id);
						$this->send($conn, 'friend-add', ['id' => $this->queryValue->idOffered]);
						break;
					}
				}
			}

			public function removeFriend(ConnectionInterface $conn): void
			{
				$id = $this->getGeneralId($conn);
				$this->mediator->general($id)->removeFriend($this->queryValue->idTarget);
				$this->mediator->general($this->queryValue->idTarget)->removeFriend($id);

				foreach ($this->clients as $client)
				{
					if ($this->getGeneralId($client) == $this->queryValue->idTarget)
					{
						$this->send($client, 'friend-remove', ['id' => $id]);
						break;
					}
				}
			}

			public function getEnemies(ConnectionInterface $conn): void
			{
				$data = [];
				$enemiesIds = $this->mediator->general($this->getGeneralId($conn))->getEnemies();
				foreach ($enemiesIds as $id)
				{
					$this->mediator->general($id);
					$data[] = $this->mediator->getDataGeneral();
				}
				$this->send($conn, 'enemies', $data);
			}

			public function getFriends(ConnectionInterface $conn): void
			{
				$data = [];
				$myId = $this->getGeneralId($conn);
				$friendsIds = $this->mediator->general($myId)->getFriends();
				foreach ($friendsIds as $id)
				{
					$this->mediator->general($id);
					$generalData = $this->mediator->getDataGeneral();

					foreach ($this->clients as $client)
					{
						if ($id == $this->getGeneralId($client))
							$generalData = array_merge($generalData, ['online' => true]);
					}
					$data[] = $generalData;
				}
				$this->send($conn, 'friends', $data);
			}

      public function addEnemy(ConnectionInterface $conn): void
      {
        $this->mediator->general($this->idGeneral)->addEnemy($this->queryValue->idTarget);
      }

      public function removeEnemy(ConnectionInterface $conn): void
      {
        $this->mediator->general($this->idGeneral)->removeEnemy($this->queryValue->idTarget);
      }
		}
	}

?>
