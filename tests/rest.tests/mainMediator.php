<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class MainMediatorTest extends TestCase
	{
    const ID_SERVER = 1;
    const ID_MAP = 1;

		public function testGeneralMediator(): void
		{
      $mediator = SOS\MainMediator::get();
      $mediator->general(1);
      $this->assertEquals($mediator->getCurrentMap(), self::ID_MAP);
      $this->assertTrue($mediator->isGeneralOnMap(self::ID_SERVER, self::ID_MAP));

      $generalData = $mediator->getDataGeneral();
      $expectedGeneralData = ['id' => 1, 'name' => 'TESTOWY', 'id_map' => 1, 'posX' => 1, 'posY' => 1, 'lvl' => 1, 'path' => 'http://play.sosgame.online/imgs/outfits/knight/'];
      $this->assertEquals($generalData, $expectedGeneralData);

      $teamData = $mediator->getDataTeam()[0];
      $expectedTeamData = ['id' => 1, 'index' => 2, 'path' => 'http://play.sosgame.online/imgs/outfits/knight/', 'name' => 'Rycerz', 'size' => 1, 'lvl' => 1, 'id_stats' => 1];
      $this->assertEquals($teamData, $expectedTeamData);
		}

    public function testItemMediator(): void
    {
      $mediator = SOS\MainMediator::get();
      $itemsData = $mediator->getDataItemsOnMap(self::ID_SERVER, self::ID_MAP);
      $expectedItemsData = [[
        'id' => 1,
        'id_pos' => 1,
        'posX' => 1,
        'posY' => 1,
        'path' => 'http://play.sosgame.online/imgs/items/',
      	'id_typeitem' => 1,
      	'id_rank' => 1,
      	'id_stats' => 1,
      	'lvl_demand' => 1,
      	'name' => 'Przedmiot 1',
      	'description' => 'Opis',
      	'cost' => 100,
      	'coins' => 2,
      	'stack' => 1,
      	'icon' => ''
      ],[
        'id' => 2,
        'id_pos' => 1,
        'posX' => 1,
        'posY' => 1,
        'path' => 'http://play.sosgame.online/imgs/items/',
      	'id_typeitem' => 1,
      	'id_rank' => 1,
      	'id_stats' => 1,
      	'lvl_demand' => 2,
      	'name' => 'Przedmiot 2',
      	'description' => 'Opis',
      	'cost' => 200,
      	'coins' => 0,
      	'stack' => 10,
      	'icon' => ''
      ]];
      $this->assertEquals($itemsData[0], $expectedItemsData[0]);
      $this->assertEquals($itemsData[1], $expectedItemsData[1]);
    }

    public function testMobsMediator(): void
    {
      $mediator = SOS\MainMediator::get();
      $mobsData = $mediator->getDataMobsOnMap(self::ID_SERVER, self::ID_MAP);
      $expectedMobsData = [
        'id' => 1,
        'name' => 'Rycerz',
        'lvl' => 1,
        'path' => 'http://play.sosgame.online/imgs/outfits/knight/',
        'relations' => 'neutral',
        'posX' => 1,
        'posY' => 1
      ];
      $this->assertEquals(count($mobsData), 1);
      $this->assertEquals($mobsData[0], $expectedMobsData);
    }
	}

?>
