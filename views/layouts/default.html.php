<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?=$this->html->charset(); ?>
	<title>Lithium Bin</title>
	<?=$this->html->style(array('lithium', 'lithium_bin')); ?>
	<?=$this->html->link('Icon', null, array('type' => 'icon')); ?>
	<?php echo $this->scripts(); ?>
	<?=$this->html->script('http://code.jquery.com/jquery-1.4.1.min.js'); ?>
	<?=$this->html->script(array('ZeroClipboard.js', 'lithium_bin.js')); ?>
</head>
<body>
	<div id="container">
		<header>
			<h1>
				<?=$this->html->link('Lithium Bin', array(
					'controller' => 'pastes', 'action' => 'add'
				)); ?>
			</h1>
			<div id="menu">
				<ul >
					<li><?php echo $this->html->link('Add', array('controller'=>'pastes', 'action' => 'add'));?></li>
					<li><?php echo $this->html->link('Latest', array('controller'=>'pastes', 'action' => 'index'));?></li>
				</ul>
			</div>
		</header>
		<div id="content">
			<?php echo $this->content; ?>
		</div>
	</div>
</body>
</html>