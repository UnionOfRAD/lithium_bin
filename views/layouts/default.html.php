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
	<title>Pastium</title>
	<?=$this->html->style(array(
		'http://lithify.me/css/lithium.css',
		'http://lithify.me/css/u1m.css',
		'lithium_bin.css'
	)); ?>
	<?=$this->html->link('Icon', null, array('type' => 'icon')); ?>
	<?php echo $this->scripts(); ?>
	<?=$this->html->script(array(
		'http://code.jquery.com/jquery-1.3.2.min.js',
		'lithium_bin.js'
	)); ?>
</head>
<body>
	<div id="container">
		<header>
			<h1>
				<?=$this->html->link('Pastium', array(
					'controller' => 'pastes', 'action' => 'add'
				)); ?>
			</h1>
			<div id="menu" class="nav">
				<ul >
					<li><?php echo $this->html->link('Latest', array(
						'controller' => 'pastes', 'action' => 'index'
					));?></li>
					<li><?php echo $this->html->link('New', array(
						'controller' => 'pastes', 'action' => 'add'
					));?></li>
				</ul>
			</div>
		</header>
		<div id="content">
			<?php echo $this->content; ?>
		</div>
	</div>
	<script>
	$(document).ready(function () {
		LithiumBin.setup({
			assetBase: '<?=$this->request()->env('base'); ?>',
		});
	});
	</script>
</body>
</html>