<?php

  require_once dirname(__FILE__).'/../../../vendor/autoload.php';
  require_once dirname(__FILE__).'/../../../src/sockets/synchro.php';

  $app = new Ratchet\App('play.sosgame.online', 7002, '0.0.0.0');
  $app->route('/synchro', new SOS\SynchroServer(2), array('*'));
  $app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
  $app->run();

?>
