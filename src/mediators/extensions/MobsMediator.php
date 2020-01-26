<?php

  namespace SOS
  {
    trait MobsMediator
    {
      private $groupOfMobs;
      private $mob;

      public function getDataMobsOnMap(int $idServer, int $idCurrentMap): array
      {
        $mobsInTotal = [];

        $this->groupOfMobs->setServerId($idServer);
        $groups = GroupOfMobs::getAliveGroups($idServer);
        foreach ($groups as $group)
        {
          $this->position->setId($group['id_position']);
          $pos = $this->position->selectThis(['id_map', 'posX', 'posY']);
          if ($pos['id_map'] == $idCurrentMap)
          {
            $this->groupOfMobs->setId($group['id']);
            $this->mob->setId($this->groupOfMobs->getAliveMobs()[0]['id']);

            $hero = $this->mob->getHeroManipulator();
            $lvl = $hero->selectThis(['lvl']);

            $character = $hero->getCharacterManipulator();
            $name = $character->selectThis(['name']);
            $path = $character->getOutfitManipulator()->getViewPath();

            unset($pos['id_map']);
            $mobsInTotal[] = array_merge(['id' => $group['id'], 'name' => $name, 'lvl' => $lvl, 'path' => $path, 'relations' => $group['relations']], $pos);
          }
        }
        return $mobsInTotal;
      }
    }
  }

?>
