<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of Rad, Inc. (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<?php
$assetHost = 'http://li3.rad-dev.org';
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset(); ?>
	<title>Pastium <?=$this->title; ?></title>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
	<?php echo $this->html->style(array(
		$assetHost . '/css/base.css',
		$assetHost . '/css/li3.css',
		'pastium'
	)); ?>
	<?php echo $this->scripts(); ?>
</head>
<body class="bin">
<div id="wrapper">
	<div id="container">
		<div id="header">
			<h1>Pastium</h1>
			<div class="nav tabs right">
				<ul>
					<li><?php echo $this->html->link('Latest', array(
						'controller' => 'pastes',
						'action' => 'index'
					), array('class' => 'index'));?></li>
					<li><?php echo $this->html->link('New', array(
						'controller' => 'pastes',
						'action' => 'add'
					), array('class'=> 'paste'));?></li>
				</ul>
			</div>
			<h2>Let there be paste!</h2>
		</div>
		<div id="content">
			<?php echo $this->content; ?>
		</div>
	</div>
	<div id="footer-spacer"></div>
</div>
<div id="footer">
	<p class="copyright">Pretty much everything is © 2009 and beyond, the Union of Rad</p>
</div>
<?php echo $this->html->script(array(
	'http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js',
	$assetHost . '/js/libraries/ZeroClipboard/ZeroClipboard.js',
	'bin'
)); ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function () {
		<?php echo 'li3Bin.setup({ text: '. (isset($binText) ? 'true' : 'false') . '});' ?>
	});
</script>
</body>
</html>