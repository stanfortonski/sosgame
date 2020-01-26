<?php

	namespace SOS
	{
		class Outfit extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'OUTFITS';
			}

			static public function getPath(): string
			{
				return Config::get()->path('game').'imgs/outfits/';
			}

			static public function add(string $name, string $src): int
			{
				return static::insertThat([null, $name, $src]);
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function getViewPath(): string
			{
				return static::getPath().$this->selectThis(['dir']).'/';
			}

			public function getNotOwnedCharacterOutfits(int $characterId, int $userId): array
			{
				return array_diff($this->getCharacterOutfitsInStore($characterId), $this->getOwnedCharacterOutfits($characterId, $userId));
			}

			public function getOwnedCharacterOutfits(int $characterId, int $userId): array
			{
				$this->useTable('OUTFITS_TO_BUY as b, OWNED_OUTFITS as o');
				$this->where('b.id_outfit = o.id_outfit AND id_character = ? AND id_user = ?', [$characterId, $userId]);
				$this->orderBy('coins');
				$outfits = $this->selectArray(['b.id_outfit as id_outfit']);
				$this->useDefaultTable();

				$character = new Character;
				$character->setId($characterId);
				$outfits = array_merge([$character->getDefaultOutfitData()['id_outfit']], $outfits);
				return $outfits;
			}

			public function getCharacterOutfitsInStore(int $characterId): array
			{
				$this->useTable('OUTFITS_TO_BUY');
				$this->where('id_character = ?', [$characterId]);
				$outfits = $this->selectArray(['id_outfit']);
				$this->useDefaultTable();
				return $outfits;
			}

			public function removeFromMarket(): void
			{
				$this->useTable('OUTFITS_TO_BUY');
				$this->where('id_outfit = ?', [$this->getId()]);
				$this->delete();
				$this->useDefaultTable();
			}

			public function removeFromOwneds(): void
			{
				$this->useTable('OWNED_OUTFITS');
				$this->where('id_outfit = ?', [$this->getId()]);
				$this->delete();
				$this->useDefaultTable();
			}

			public function removeAllOutfitData(): void
			{
				$this->removeFromMarket();
				$this->removeFromOwneds();
				$this->deleteThis();
			}

			public function addToStore(int $characterId, int $coins): void
			{
				$this->useTable('OUTFITS_TO_BUY');
				$this->insert([$characterId, $this->getId(), $coins]);
				$this->useDefaultTable();
			}

			public function addToOwned(int $userId): void
			{
				$this->useTable('OWNED_OUTFITS');
				$this->insert([$userId, $this->getId()]);
				$this->useDefaultTable();
			}
		}
	}

?>
