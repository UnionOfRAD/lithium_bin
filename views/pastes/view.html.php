<div class="paste">
	<h2>This <span class="language"><?=$paste->language;?></span> paste was created by
		<span class="author user"><?=$paste->author;?></span> at
		<span class="created"><?=$paste->created;?></span>
	</h2>
	<div class="nav">
		<ul>
			<li>
				<?=$this->html->link('Color', '#', array(
					'id' => 'toggle-color',
					'title' => 'Toggle between raw and highlighted mode.'
				)); ?>
			</li>
			<li>
				<?=$this->html->link('Larger', '#', array(
					'id' => 'code-bigger',
					'title' => "Increases the code's font size."
				)); ?>
			</li>
			<li>
				<?=$this->html->link('Smaller', '#', array(
					'id' => 'code-smaller',
					'title' => "Decreases the code's font size."
				)); ?>
			</li>
			<li>
				<?=$this->html->link('Copy', '#', array(
					'id' => 'copy-to-clipboard',
					'title' => 'Copy to clipboard.'
				)); ?>
			</li>
			<li>
				<?=$this->html->link('Edit', array(
					'controller' => 'pastes', 'action' => 'edit', 'args' => array($paste->id)
				), array(
					'id' => 'code-edit',
					'title' => 'Edit this paste.'
				)); ?>
			</li>
			<li>
				<?=$this->html->link('Wrap Text', '#', array(
					'id' => 'textwrap',
					'title' => 'Wraps text in <pre> blocks.'
				)); ?>
			</li>
		</ul>
	</div>
	<div class="raw"><pre><code><?=$paste->content;?></code></pre></div>
	<div class="parsed"><?php echo $paste->parsed;?></div>
</div>