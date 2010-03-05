<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use \lithium\net\http\Media;

$default = Media::type('default');
$text = Media::type('text');

Media::type('xml', null, $default['options']);
Media::type('txt', null, $text['options']);

?>