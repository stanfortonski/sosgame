<?php

  namespace SOS
  {
    trait ItemMediator
    {
      private $item;

      private Function takeItem(int $idItem, int $idServer, int $idPosition): void
      {
        $this->item->setId($idItem);
        $this->item->takeItemFromGround($idServer, $idPosition);
      }

      public function getDataItemsOnMap(int $idServer, int $idCurrentMap): array
      {
        $items = [];
        $data = Item::getAllItemsOnEveryGround($idServer);
        foreach ($data as $item)
        {
          $this->position->setId($item['id_position']);
          $idMap = $this->position->selectThis(['id_map']);

          if ($idCurrentMap == $idMap)
          {
            $this->item->setId($item['id_item']);
            $itemData = $this->item->selectThis();
            $pos = $this->position->selectThis(['id AS id_pos', 'posX', 'posY']);
            $icon = ['path' => $this->item->getIconPath()];

            $items[] = array_merge($itemData, $pos, $icon);
          }
        }
        return $items;
      }
    }
  }

?>
