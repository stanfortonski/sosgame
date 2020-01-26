<?php

  require_once dirname(__FILE__).'/../src/include.php';

  $connection = new DBManagement\DatabaseConnection;
  $connection->selectDB('sosgame_test');

?>
