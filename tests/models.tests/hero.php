<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class HeroTest extends TestCase
	{
		static $hero;

		public function testAdddingHero(): void
		{
			self::$hero = new SOS\Hero;
			$character = new SOS\Character;
			$character->setId(1);

			$idHero = SOS\Hero::addHero($character->getId());
			self::$hero->setId($idHero);
			$hero = self::$hero->selectThis();
			$this->assertNotEmpty($hero);
			$this->assertNotEmpty(self::$hero->getStatsManipulator()->selectThis());
			$this->assertEquals($hero['lvl'], $character->selectThis(['lvl_min']));
		}

		/**
		 * @depends testAdddingHero
		 */
		public function testNextHerosLevel(): void
		{
			$oldLvl = self::$hero->selectThis(['lvl']);
			$exp = 10 * $oldLvl;

			self::$hero->addExp($exp);
			$this->assertTrue(self::$hero->nextLevel($exp));
			$newLvl = self::$hero->selectThis(['lvl']);

			$this->assertTrue($newLvl > $oldLvl);
			$this->assertEquals(self::$hero->selectThis(['exp']), 0);

			self::$hero->addExp(1);
			$this->assertFalse(self::$hero->nextLevel($exp));
			$this->assertEquals(self::$hero->selectThis(['exp']), 1);
		}

		/**
		 * @depends testAdddingHero
		 */
		public function testRemovingHerosData(): void
		{
			$statsManip = self::$hero->getStatsManipulator();
			self::$hero->removeAllHeroData();

			$this->assertEmpty(self::$hero->selectArrayThis());
			$this->assertEmpty($statsManip->selectArrayThis());
		}
	}

?>
