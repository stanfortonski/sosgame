<?php

  namespace SOS
  {
    class Articles extends DatabaseOperationsAdapter
    {
      private $perPage;

      static public function getTable(): string
      {
        return 'ARTICLES';
      }

      public function __construct(int $perPage)
      {
        parent::__construct();
        $this->perPage = $perPage;
        if ($this->perPage <= 0)
          $this->perPage = 1;
      }

      public function getAmountOfArticles(): int
      {
        return $this->select(['COUNT(*)']);
      }

      public function getAmountOfPages(): int
      {
        $amount = ceil($this->getAmountOfArticles()/$this->perPage);
        return $amount;
      }

      public function getArticlesForPage(int $page): array
      {
        $endIndex = $page*$this->perPage;
        if ($endIndex < 0 || $endIndex > $this->getAmountOfArticles())
          $endIndex = 0;

        $this->limit($endIndex, $this->perPage);
        $this->orderBy('`date` DESC');
        return $this->selectArray();
      }
    }
  }

?>
