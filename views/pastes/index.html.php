<br><br><br>
<?php if ($latest == null) {
	echo 'NO PASTES';
} else {
	echo '<ul>';

	foreach ($latest->rows as $row) {
		echo '<li>'.$this->html->link($row->id, '/view/'.$row->id).'</li>';
	}

	echo '</ul>';
}
?>