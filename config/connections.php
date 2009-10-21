<?php

use \lithium\data\Connections;

Connections::add('couch', 'http', array(
	'adapter' => 'Couch',
	'host' => '127.0.0.1',
	'port' => 5984,
));
?>