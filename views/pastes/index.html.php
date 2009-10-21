<br><br><br>
<ul>
<?php
	foreach ($latest->rows as $row) {
		echo '<li>'.$this->html->link($row->id, '/view/'.$row->id).'</li>';
	}
?>
</ul>
<pre><code>
<?php
	print_r($latest);
?></code></pre>