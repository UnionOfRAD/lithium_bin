<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use \lithium\http\Router;

/**
 * Uncomment the line below to enable routing for admin actions.
 * @todo Implement me.
 */
// Router::namespace('/admin', array('admin' => true));

Router::connect('/test/{:args}', array('controller' => '\lithium\test\Controller'));
Router::connect('/test', array('controller' => '\lithium\test\Controller'));

Router::connect('/', array('controller' => 'pastes', 'action' => 'add'));
Router::connect('/latest', array('controller' => 'pastes', 'action' => 'index'));
Router::connect('/pastes/index/page:{:page:[0-9]+}', array('controller' => 'pastes', 'action' => 'index'), array('page' => 1));
Router::connect('/pastes/index/page:{:page:[0-9]+}/limit:{:limit:[0-9]+}', array('controller' => 'pastes', 'action' => 'index'), array('page' => 1, 'limit' => 10));


Router::connect('/add/{:args}', array('controller' => 'pastes', 'action' => 'add'));
Router::connect('/edit/{:args}', array('controller' => 'pastes', 'action' => 'edit'));
Router::connect('/view/{:args}', array('controller' => 'pastes', 'action' => 'view'));


/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'view'));

/**
 * Finally, connect the default routes.
 */
Router::connect('/{:controller}/{:action}/{:id:[0-9]+}.{:type}', array('id' => null));
Router::connect('/{:controller}/{:action}/{:id:[0-9]+}');
Router::connect('/{:controller}/{:action}/{:args}');
?>