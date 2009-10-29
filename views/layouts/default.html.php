<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?=@$this->html->charset(); ?>
	<title>Pastium <?=@$this->title(); ?></title>
	<?=@$this->html->link('Icon', null, array('type' => 'icon')); ?>
	<?=@$this->html->style(array(
		'http://li3.rad-dev.org/css/li3.css',
		'pastium'
	)); ?>
	<?php
		if (!empty($paste->language)) {
			echo $this->html->style('syntax.' . $paste->language);
		}
	?>
	<?=@$this->scripts(); ?>
</head>
<body class="pastebin">
	<div class="width-constraint">
		<div class="article">
			<h1>Pastium</h1>
			<div class="nav tabs right">
				<ul>
					<li><?=@$this->html->link('Latest', array(
						'controller' => 'pastes',
						'action' => 'index'
					), array('class' => 'index'));?></li>
					<li><?=@$this->html->link('New', array(
						'controller' => 'pastes',
						'action' => 'add'
					), array('class'=> 'paste'));?></li>
				</ul>
			</div>
			<h2>Let there be paste!</h2>
			<?=@$this->content();?>
		</div>
	</div>

	<div class="footer" id="site-footer">
		<p class="copyright">Pretty much everything is © 2009 and beyond, the Union of Rad</p>
	</div>
	<?=@$this->html->script(array(
		'http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js',
		'http://li3.rad-dev.org/js/li3.js',
		'http://li3.rad-dev.org/libraries/ZeroClipboard/ZeroClipboard.js',
		'bin'
	)); ?>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function () {
			li3.setupFooter();
			<?php echo !empty($binJs) ? 'li3Bin.setup({ text: '.(($binText) ? 'true' : 'false' ).'});' : null ; ?>
		});
	</script>
</body>
</html>