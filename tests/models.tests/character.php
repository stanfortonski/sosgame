<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class CharacterTest extends TestCase
	{
		static $char;

		public function testAddingCharacter(): void
		{
			$data = [
				'id_stats_updater' => 1,
				'id_outfit' => 8,
				'is_temp' => 0,
				'name' => 'name',
				'targets' => 1,
				'range' => 'any',
				'cost' => 100,
				'coins' => 0,
				'lvl_min' => 1,
				'lvl_max' => 10,
				'size_in_team' => 1
			];

			$id = SOS\Character::addCharacter($data);
			self::$char = new SOS\Character;
			self::$char->setId($id);

			$characterData = self::$char->selectThis();
			$this->assertNotEmpty($characterData);
			unset($characterData['id']);
			unset($characterData['id_default_stats']);
			$this->assertEquals($characterData, $data);
		}

		/**
     * @depends testAddingCharacter
     */
		public function testRemovingCharacter(): void
		{
			self::$char->removeAllCharacterData();
			$this->assertEmpty(self::$char->selectThis());
		}
	}

?>
