<?php if (!$paste->author && $paste->author == '') { ?>
	<div class="author">This <strong><?=$paste->language;?></strong> paste was created
		<strong>anonymously</strong>
		on <strong><?=$paste->created;?></strong>.</div>
<?php } else  { ?>
	<div class="author">This <strong><?=$paste->language;?></strong> paste was created by
		<strong><?=$paste->author;?></strong> on
		<strong><?=$paste->created;?></strong>.</div>
<?php } ?>

<div class="nav tabs light">
	<ul>
		<li>
			<a class="color" href="#" id="toggle-color" title="Toggle Color">
				<span>Color</span>
			</a>
		</li>
		<li>
			<a class="contrast" href="#" id="toggle-contrast" title="Toggle Contrast">
				<span>Contrast</span>
			</a>
		</li>
		<li>
			<a class="bigger" href="#" id="code-bigger" title="Larger code">
				<span>Larger</span>
			</a>
		</li>
		<li>
			<a class="smaller" href="#" id="code-smaller" title="Smaller code">
				<span>Smaller</span>
			</a>
		</li>
		<li>
			<a class="copy" href="#" id="copy-to-clipboard" title="Copy to clipboard">
				<span>Copy</span>
			</a>
		</li>
		<li>
			<a class="edit" href="<?php echo $this->_request->env('base');?>/edit/<?=$paste->id;
			?>" id="code-edit" title="Edit paste">
				<span>Edit</span>
			</a>
		</li>
	</ul>
</div>

<div id="clean" class="section code" style="display:none;"><pre><code><?php echo $paste->content;?></code></pre></div>
<div id="paste" class="section code"><?php echo $paste->parsed;?></div>