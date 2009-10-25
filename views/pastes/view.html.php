<div class="view">
<?php if (empty($paste->author)) { ?>
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
		<?=@$this->html->link('edit', array(
			'controller' => 'pastes', 'action' => 'edit', 'args' => array($paste->_id)
		));?>
	</p>
	<div id="clean" ><pre><code><?=$paste->content;?></code></pre></div>
	<div id="paste"><?=@$paste->parsed;?></div>
</div>