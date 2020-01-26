<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class OutfitTest extends TestCase
	{
		const USER = 1;
		const CHARACTER = 1;

		static $outfit;

		private function clearTestBeforeStart(): void
		{
			self::$outfit = new SOS\Outfit;
			$outfits = self::$outfit->select();

			self::$outfit->setId(end($outfits)['id']);
			self::$outfit->removeAllOutfitData();

			self::$outfit->setId(4);
			self::$outfit->removeFromOwneds();
		}

		public function testAddingOutfit(): void
		{
			$this->clearTestBeforeStart();

			$name = 'SOS';
			$src = 'NOT_SOS';

			$oldOutfits = self::$outfit->select();
			SOS\Outfit::add($name, $src);
			$outfits = self::$outfit->select();

			$this->assertNotEquals($outfits, $oldOutfits);

			$skin = end($outfits);
			$this->assertNotEquals($skin['id'], 4);
			$this->assertEquals($skin['name'], $name);
			$this->assertEquals($skin['dir'], $src);
		}

		/**
		 * @depends testAddingOutfit
		 */
		public function testOwningOutfit(): void
		{
			$store = self::$outfit->getCharacterOutfitsInStore(self::USER);
      $this->assertEquals($store, [3, 4]);

			$default = self::$outfit->getOwnedCharacterOutfits(self::CHARACTER, self::USER);
      $this->assertEquals($default, [1, 3]);

			$notOwned = self::$outfit->getNotOwnedCharacterOutfits(self::CHARACTER, self::USER);
      $this->assertEquals($notOwned, ['1' => 4]);

      self::$outfit->setId(4);
      self::$outfit->addToOwned(self::USER);
      $owned = self::$outfit->getOwnedCharacterOutfits(self::CHARACTER, self::USER);
      $this->assertEquals($owned, [1, 3, 4]);
    }

		/**
		 * @depends testAddingOutfit
		 */
		public function testRemovingOutfits(): void
		{
			self::$outfit->setId(5);
			self::$outfit->removeAllOutfitData();

			$outfits = self::$outfit->select();
			$this->assertNotEquals(end($outfits)['id'], 4);
		}
	}

?>
