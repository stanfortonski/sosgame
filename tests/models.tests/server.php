<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class ServerTest extends TestCase
	{
		public function testAvailableOfNamesOnTheServer(): void
		{
			$server = new SOS\Server;
			$server->setId(1);
			$this->assertFalse($server->isNameAvailable('TESTOWY'));
			$this->assertTrue($server->isNameAvailable('TESTOWY2'));
		}
	}

?>
