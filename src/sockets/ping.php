<?php

	namespace SOS
	{
		require_once dirname(__FILE__).'/../include.php';

		use Ratchet\MessageComponentInterface;
		use Ratchet\ConnectionInterface;

		class PingServer implements MessageComponentInterface
		{
			public function __construct(){}
			public function onOpen(ConnectionInterface $conn){}
			public function onClose(ConnectionInterface $conn){}
			public function onError(ConnectionInterface $conn, \Exception $e){}

			public function onMessage(ConnectionInterface $conn, $msg)
			{
				$conn->send($this->getPing($conn));
			}

			public function getPing(ConnectionInterface $conn): string
			{
				$pingCommand = `ping -n 1 {$conn->remoteAddress}`;
				preg_match_all('/[0-9]{1,}ms/', $pingCommand, $out);
				if (!empty($out[0][2]))
					return $out[0][2];
				return '';
			}
		}
	}

?>
