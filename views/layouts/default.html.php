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
		'http://code.jquery.com/jquery-1.4.1.min.js',
		'lithium_bin.js',
		'jquery.timeago.js',
		'ZeroClipboard.js'
	)); ?>
</head>
<body>
<div id="wrapper">
	<div id="container">
		<header>
			<?=$this->html->image('http://lithify.me/img/pastium-logo.png', array(
				'width' => 90, 'height' => 93, 'alt' => 'logo', 'class' => 'logo'
			)); ?>
			<h1>
				<?=$this->html->link('Pastium', array(
					'controller' => 'pastes', 'action' => 'add'
				)); ?>
			</h1>
			<h2>Let there be paste!</h2>
			<div id="menu" class="nav capsule">
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
	<div id="footer-spacer"></div>
</div>
<div id="footer">
	<p class="copyright">
		<?= sprintf('Pretty much everything is © %d and beyond, the Union of Rad',
			date('Y')
		); ?>
	</p>
</div>
<script>
$(document).ready(function () {
	LithiumBin.setup({
		assetBase: '<?=$this->request()->env('base'); ?>',
	});
	$('time').timeago();
});
</script>
</body>
</html>