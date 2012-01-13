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
	<?=$this->html->style(array('lithium', 'lithium_bin')) ?>
	<?=$this->html->link('Icon', null, array('type' => 'icon')) ?>
</head>
<body>
	<div id="container">
		<header>
			<h1><?=$this->html->link('Lithium Bin', 'Pastes::add') ?></h1>
			<div id="menu" class="nav">
				<ul>
					<li><?= $this->html->link('Latest', 'Pastes::index') ?></li>
					<li><?= $this->html->link('New', 'Pastes::add') ?></li>
				</ul>
			</div>
		</header>
		<div id="content">
			<?=$this->content(); ?>
		</div>
	</div>
	<?=$this->scripts() ?>
	<?=$this->html->script(array(
		'http://code.jquery.com/jquery-1.7.1.min.js',
		'lithium_bin.js'
	)) ?>
	<script>$(function(){
		LithiumBin.setup()
	})</script>
</body>
</html>