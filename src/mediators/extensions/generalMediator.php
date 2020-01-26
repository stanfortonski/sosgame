<?php

  namespace SOS
  {
    trait GeneralMediator
    {
      private $general;

      public function general($id = null): General
      {
        if (!empty($id))
          $this->general->setId($id);
        return $this->general;
      }

      public function getCurrentMap(): int
      {
        $this->position->setId($this->general->selectThis(['id_position']));
        return $this->position->selectThis(['id_map']);
      }

      public function isGeneralOnMap(int $idGeneral, int $idCurrentMap): bool
      {
        $oldId = $this->general->getId();
        $this->general->setId($idGeneral);
        $idMap = $this->getCurrentMap();
        $result = $idMap == $idCurrentMap;
        $this->general->setId($oldId);
        return $result;
      }

      public function getDataGeneral(): array
      {
        $generalData = $this->general->selectThis(['id', 'id_hero', 'name', 'id_position']);

        $this->hero->setId($generalData['id_hero']);
        $lvl = $this->hero->selectThis(['lvl']);
        unset($generalData['id_hero']);

        $this->position->setId($generalData['id_position']);
        $pos = $this->position->selectThis(['id_map', 'posX', 'posY']);
        $path = $this->general->getOutfitManipulator()->getViewPath();

        unset($generalData['id_position']);
        return array_merge($generalData, $pos, ['path' => $path, 'lvl' => $lvl]);
      }

      public function getDataTeam(): array
      {
        $rows = [];
        $team = $this->general->getTeamManipulator();
        $generalHeroId = $this->general->getHeroManipulator()->getId();

        $teamMates = $team->getAllItems();
        $count = count($teamMates);
        for ($i = 0; $i < $count; ++$i)
        {
          $teamMate = $teamMates[$i];
          $index = $teamMate['index'];

          $this->hero->setId($teamMate['id_subject']);
          $character = $this->hero->getCharacterManipulator();

          $heroData = $this->hero->selectThis(['id_stats', 'lvl']);
          $charData = $character->selectThis(['name', 'size_in_team as size']);

          if ($teamMate['id_subject'] == $generalHeroId)
            $path = $this->general->getOutfitManipulator()->getViewPath();
          else $path = $character->getOutfitManipulator()->getViewPath();

          $rows[] = array_merge(['id' => $teamMate['id_subject'], 'index' => $index, 'path' => $path], $heroData, $charData);
        }
        return $rows;
      }
    }
  }

?>
