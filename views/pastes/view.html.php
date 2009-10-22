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

	$('#placeholder').each(function() {
		var clip = new ZeroClipboard.Client();
		clip.glue($('#placeholder')[0]);
		clip.setText($('#clean').text());
		clip.addEventListener('complete', function(client) {
			alert("Copied text to clipboard!");
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

	<table>
		<tr>
			<td><a href="#" id="toggle">clean</a></td>
			<td id="placeholder"><a href="#">copy</a></td>
			<td><?=@$this->html->link('edit', array(
				'controller' => 'pastes',
				'action' => 'edit',
				'args' => array($paste->_id)
			));?></td>
		</tr>
	</table>
	<div id="clean" ><pre><code><?=$paste->content;?></code></pre></div>
	<div id="paste"><?=@$paste->parsed;?></div>
</div>