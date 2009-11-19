<?php
/**
 * Lithium: the most rad php framework
 * Copyright 2009, Union of Rad, Inc. (http://union-of-rad.org)
 *
 * Licensed under The BSD License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2009, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset(); ?>
	<title>Li3 Paste Bin</title>
	<?php echo $this->html->style('bin.0.3'); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
	<?php echo $this->html->script(array('jquery-1.3.2.min.js', 'ZeroClipboard.js', 'bin.js')); ?>
</head>
<body>
	<div id="container">
		<div id="header">
			<?php echo $this->html->image('lithium-logo.png');?>
			<div id="menu">
				<ul >
					<li><?php echo $this->html->link('Add new', array('controller'=>'pastes', 'action' => 'add'));?></li>
					<li><?php echo $this->html->link('Latest', array('controller'=>'pastes', 'action' => 'index'));?></li>
				</ul>
			</div>
		</div>
		<div id="content">
			<?php echo $this->content; ?>
		</div>
		<div id="footer">
			@2009 Union of Rad
		</div>
	</div>
</body>
</html>