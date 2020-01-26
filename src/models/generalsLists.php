<?php
  namespace SOS
  {
    trait GeneralsLists
    {
      public function isFriend(int $idGen): bool
      {
        return $this->isInGeneralsList('FRIENDS_LIST', $idGen);
      }

      public function addFriend(int $idGen): void
      {
        $this->addToGeneralsList('FRIENDS_LIST', $idGen);
      }

      public function removeFriend(int $idGen): void
      {
        $this->removeFromGeneralsList('FRIENDS_LIST', $idGen);
      }

      public function getFriends(): array
      {
        return $this->getGeneralsList('FRIENDS_LIST');
      }

      public function isEnemy(int $idGen): bool
      {
        return $this->isInGeneralsList('ENEMIES_LIST', $idGen);
      }

      public function addEnemy(int $idGen): void
      {
        $this->addToGeneralsList('ENEMIES_LIST', $idGen);
      }

      public function removeEnemy(int $idGen): void
      {
        $this->removeFromGeneralsList('ENEMIES_LIST', $idGen);
      }

      public function getEnemies(): array
      {
        return $this->getGeneralsList('ENEMIES_LIST');
      }

      private function isInGeneralsList(string $table, int $idGen): bool
      {
        $this->useTable($table);
        $this->where('id = ? AND id_general = ?',  [$this->getId(), $idGen]);
        $value = !empty($this->select());
        $this->useDefaultTable();
        return $value;
      }

      private function addToGeneralsList(string $table, int $idGen): void
      {
        if (!$this->isInGeneralsList($table, $idGen))
        {
          $this->useTable($table);
          $this->insert([$this->getId(), $idGen]);
          $this->useDefaultTable();
        }
      }

      public function removeFromGeneralsList(string $table, int $idGen): void
      {
        $this->useTable($table);
        $this->where('id = ? AND id_general = ?', [$this->getId(), $idGen]);
        $this->delete();
        $this->useDefaultTable();
      }

      private function getGeneralsList(string $table): array
      {
        $this->useTable($table);
        $result = $this->selectArrayThis(['id_general']);
        $this->useDefaultTable();
        return $result;
      }
    }
  }

?>
