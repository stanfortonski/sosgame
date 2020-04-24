<?php

  require_once dirname(__FILE__).'/../../vendor/autoload.php';
  require_once dirname(__FILE__).'/../../src/sockets/ping.php';

  $app = new Ratchet\App('play.sosgame.online', 90, '0.0.0.0');
  $app->route('/ping', new SOS\PingServer, array('*'));
  $app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
  $app->run();

?>
