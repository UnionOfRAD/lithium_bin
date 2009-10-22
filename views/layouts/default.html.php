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
	<?=@$this->html->link('Icon', 'http://li3.rad-dev.org/favicon.png', array('type' => 'icon')); ?>
	<?=@$this->html->style(array(
		'http://li3.rad-dev.org/css/base.css', 
		'http://li3.rad-dev.org/css/li3.css',
		'bin'
	)); ?>
	<?php
		if (!empty($paste->language)) {
			echo $this->html->style('syntax.' . $paste->language);
		}
	?>
	<?=@$this->scripts(); ?>
</head>
<body class="pastebin">
	<div class="header" id="site-header">
		<div class="aside" id="cli">
			<div class="nav">
				<div id="cli-display"></div>
				<div>
					<form id="cli-form" onSubmit="return false">
						<input type="text" id="cli-input" />
						<input id="cli-submit" type="submit" />
					</form>
				</div>
			</div>
		</div>
		<div class="aside" id="git-shortcuts">
			<span id="git-clone-path" class="clone">git clone code@rad-dev.org:lithium.git</span>
			<div class="nav">
				<?php /*<a href="#" class="download" title="Download Lithium">download</a> */ ?>
				<a href="#" id="git-copy" class="copy" title="Copy the git clone shortcut to your clipboard">
					copy to clipboard
				</a>
			</div>
		</div>
		<div>
			<h1><?=@$this->html->link('Lithium', '/'); ?></h1>
		</div>
	</div>

	<div class="width-constraint">
		<div class="article">
			<h1>Pastium</h1>
			<div class="nav tabs right">
				<ul>
					<li><?=@$this->html->link('New', array(
						'controller' => 'pastes', 
						'action' => 'add'
					));?></li>
					<li><?=@$this->html->link('Latest', array(
						'controller' => 'pastes', 
						'action' => 'index'
					));?></li>
				</ul>
			</div>
			<?=@$this->content();?>
		</div>
	</div>

	<div class="footer" id="site-footer">
		<p class="copyright">Pretty much everything is © 2009 and beyond, the Union of Rad</p>
	</div>
	<?=@$this->html->script(array(
		'http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js',
		'http://li3.rad-dev.org/js/li3.js',
		'http://li3.rad-dev.org/js/cli.js',
		'http://li3.rad-dev.org/libraries/ZeroClipboard/ZeroClipboard.js',
		'bin'
	)); ?>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function () {
			li3.setup({
				base : '<?php echo $this->_request->env('base');?>',
				testimonials: <?php echo !empty($testimonials) ? 'true' : 'false'; ?>
			});
			li3Cli.setup();
			<?php echo !empty($binJs) ? 'li3Bin.setup();' : null ; ?>
		});
	</script>
</body>
</html>
