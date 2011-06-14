<?php
$nickRgb = function($nick) {
	$hash = abs(crc32($nick));

	$rgb = array($hash % 255, $hash % 255, $hash % 255);
	$rgb[$hash % 2] = 0;

	return $rgb;
};
?>
<h2><?=$this->title('View Paste'); ?></h2>
<div class="paste">
	<div class="meta">
		This
		<?php if ($paste->private): ?><span class="flag">private</span><?php endif; ?>
		<?php if ($paste->immutable): ?><span class="flag">immutable</span><?php endif; ?>
		<span class="language"><?=$paste->language;?></span> paste was created by
		<span class="author user" style="color: rgb(<?=implode(',' , $nickRgb($paste->author))?>);"><?=$paste->author;?></span>
		<time datetime="<?=date('c', strtotime($paste->created)); ?>" class="created">at <?=$paste->created;?></time>.
	</div>
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
			<?php if (!$paste->immutable): ?>
				<li>
					<?=$this->html->link('Edit', array(
						'controller' => 'pastes', 'action' => 'edit', 'args' => array($paste->id)
					), array(
						'id' => 'code-edit',
						'title' => 'Edit this paste.'
					)); ?>
				</li>
			<?php endif; ?>
			<li>
				<?=$this->html->link('Wrap Text', '#', array(
					'id' => 'textwrap',
					'title' => 'Wraps text in <pre> blocks.'
				)); ?>
			</li>
		</ul>
	</div>
	<div class="raw"><pre><code><?php echo $h($paste->content);?></code></pre></div>
	<div class="parsed"><?php echo $paste->parsed;?></div>
</div>