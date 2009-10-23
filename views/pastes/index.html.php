<br><br><br>
<?php if ($latest == null) {
	echo 'NO PASTES';
} else {
	echo '<ul>';

	foreach ($latest->rows as $row) {
		echo '<li style="margin-top: 15px;">';
		echo $row->value->author.' '.$row->value->created.' '.$row->value->language. ' '.
			$this->html->link('View', '/view/'.$row->id);
		echo '<hr>'.$row->value->preview;
		echo '</li>';
	}

	echo '</ul>';
}
?>