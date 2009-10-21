<h2>Latest Pastes</h2>

<ul class="pastes">
<?php
	foreach ($latest->rows as $row) {
		echo '<li>'.$this->html->link($row->id, '/view/'.$row->id).'</li>';
	}
?>
</ul>
