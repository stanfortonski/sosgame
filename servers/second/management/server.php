<?php

  require_once dirname(__FILE__).'/../../../vendor/autoload.php';
  require_once dirname(__FILE__).'/../../../src/sockets/management.php';

  $app = new Ratchet\App('play.sosgame.online', 8082, '0.0.0.0');
  $app->route('/management', new SOS\ManagementServer(2), array('*'));
  $app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
  $app->run();

?>
