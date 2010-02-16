<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use \lithium\data\Connections;

Connections::add('default', 'http', array(
	'adapter' => 'CouchDb',
	'host' => '127.0.0.1',
	'port' => '5984'
));

Connections::add('test', 'http', array(
	'adapter' => 'CouchDb',
	'host' => '127.0.0.1',
	'port' => '5984'
));
?>