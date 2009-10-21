<?php if (empty($paste->author)) { ?>
	<h2>This <strong><?=@$paste->language;?></strong> paste was created
		<strong>anonymously</strong>
		on <strong><?=@$paste->created;?></strong></h2>
<?php } else  { ?>
	<h2>This <strong><?=@$paste->language;?></strong> paste was created by
		<strong><?=@$paste->author;?></strong> on
		<strong><?=@$paste->created;?></strong></h2>
<?php } ?>

<nav class="tabs light">
	<ul>
		<li><a class="color" href="#" id="toggle-color" title="Toggle Color"><span>Color</span></a></li>
		<li><a class="contrast" href="#" id="toggle-contrast" title="Toggle Contrast"><span>Contrast</span></a></li>
		<li><a class="bigger" href="#" id="code-bigger" title="Larger code"><span>Larger</span></a></li>
		<li><a class="smaller" href="#" id="code-smaller" title="Smaller code"><span>Smaller</span></a></li>
		<li><a class="copy" href="#" id="copy-to-clipboard" title="Copy to clipboard"><span>Copy</span></a></li>
	</ul>
</nav>

<section id="clean" class="code"><pre><code><?=@$paste->content;?></code></pre></section>
<section id="paste" class="code"><?=@$paste->parsed;?></section>
	
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		li3Bin.setup();
	});
</script>
