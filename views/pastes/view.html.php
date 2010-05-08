<div class="view">
	<h2><?=$paste->preview;?></h2>
<?php if (!$paste->author || $paste->author == '') { ?>
	<h3>This <strong><?=$paste->language;?></strong> paste was created
		<strong>anonymously</strong>
		at <strong><?=$paste->created;?></strong></h3>
<?php } else  { ?>
	<h3>This <strong><?=$paste->language;?></strong> paste was created by
		<strong><?=$paste->author;?></strong> at
		<strong><?=$paste->created;?></strong></h3>
<?php } ?>
	<p>
		<a href="#" id="toggle">clean</a> &middot;
		<a href="#">copy</a> &middot;
		<?php
		if (isset($paste->password) && !empty($paste->password)) {

			echo $this->html->link('edit', array(
				'controller' => 'pastes', 'action' => 'edit', 'args' => array($paste->id)
			));
			echo ' &middot; <small>Password protected</small>';
		} else
			echo $this->html->link('edit', array(
				'controller' => 'pastes', 'action' => 'edit', 'args' => array($paste->id)
			));
		?>
	</p>
	<div id="clean" ><pre><code><?=$paste->content;?></code></pre></div>
	<div id="paste"><?php echo $paste->parsed;?></div>
</div>