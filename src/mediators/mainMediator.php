<?php

  namespace SOS
  {
    class MainMediator
    {
      static private $instance;
      use ItemMediator, MobsMediator, GeneralMediator;

      private $position;

      static public function get(): self
      {
        if (empty(static::$instance))
          static::$instance = new static;
        return static::$instance;
      }

      protected function __construct()
      {
        $this->item = new Item;
        $this->position = new Position;
        $this->groupOfMobs = new GroupOfMobs;
        $this->mob = new Mob;
        $this->general = new General;
        $this->hero = new Hero;
      }
    }
  }

?>
