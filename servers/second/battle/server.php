<?php

  require_once dirname(__FILE__).'/../../../vendor/autoload.php';
  require_once dirname(__FILE__).'/../../../src/sockets/battle.php';

  $app = new Ratchet\App('play.sosgame.online', 9002, '0.0.0.0');
  $app->route('/battle', new SOS\BattleServer(2), array('*'));
  $app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
  $app->run();

?>
