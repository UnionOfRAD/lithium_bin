<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\net\http\Router;

Router::connect('/', array('controller' => 'pastes', 'action' => 'add'));

Router::connect('/add', array('controller' => 'pastes', 'action' => 'add'));
Router::connect('/add/{:args}', array('controller' => 'pastes', 'action' => 'add'));

Router::connect('/edit/{:args}', array('controller' => 'pastes', 'action' => 'edit'));

Router::connect('/latest', array('controller' => 'pastes', 'action' => 'index'));
Router::connect('/latest/page:{:page:[0-9]+}', array(
	'controller' => 'pastes', 'action' => 'index', 'page' => 1
));
Router::connect('/latest/page:{:page}/limit:{:limit}', array(
	'controller' => 'pastes', 'action' => 'index', 'page' => 1, 'limit' => 10
));

Router::connect('/{:controller}/{:action}/{:args}');

?>