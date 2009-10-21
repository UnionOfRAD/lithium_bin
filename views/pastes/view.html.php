<div class="view">
<?php if (empty($paste->author)) { ?>
	<h3>This <strong><?=@$paste->language;?></strong> paste was created
		<strong>anonymously</strong>
		at <strong><?=@$paste->created;?></strong></h3>
<?php } else  { ?>
	<h3>This <strong><?=@$paste->language;?></strong> paste was created by
		<strong><?=@$paste->author;?></strong> at
		<strong><?=@$paste->created;?></strong></h3>
<?php } ?>
	<br>
	<script>
var toogle = false;
$(document).ready(function() {
	$('div#clean').hide();

	$('a#copy').add(function() {
		var clip = new ZeroClipboard.Client();
		var div = $('div#clean');
		clip.glue(div);
		clip.setText(div.text);
		//Add a complete event to let the user know the text was copied
		clip.addEventListener('complete', function(client) {
			alert("Copied text to clipboard!\n");
		});
	});
	$('a#toggle').click(function() {
		if (toogle) {
			$('div#clean').hide();
			$('div#paste').show();
			$(this).text('clean');
		} else {
			$('div#clean').show();
			$('div#paste').hide();
			$(this).text('highlight');
		}
		toogle = !toogle;
		return false;
	});

});
	</script>
	<a href="#" id="toggle">clean</a> <a href="#" id="copy">copy</a>
	<div id="clean" ><pre><code><?=$paste->content;?></code></pre></div>
	<div id="paste"><?=@$paste->parsed;?></div>
</div>