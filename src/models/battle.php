<?php

	namespace SOS
	{
		class Battle extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'BATTLES';
			}

      static public function getBattle(int $idTeam): array
      {
        $db = new self;
        $db->where('id_team = ? OR (id_group = ? AND type = "PVP")', [$idTeam, $idTeam]);
        return $db->select();
      }

      static public function isBattle(int $idTeam): bool
      {
        return !empty(self::getBattle($idTeam));
      }

      static public function autoEndBattles(): void
      {
        $db = new self;
        $db->where('end_date <= NOW()');
				$db->delete();
      }

      public function __construct()
			{
				parent::__construct();
			}

			public function startBattle(string $type, int $idTeam, int $idGroup): void
			{
        $this->insert([$idTeam, $idGroup, $type, null, date('Y-m-d H:i:s')]);
      }

      public function endBattle(int $idTeam, int $idGroup): void
      {
        $this->where('id_team = ? OR id_group = ?', [$idTeam, $idGroup]);
        $this->delete();
      }
    }
  }

?>
