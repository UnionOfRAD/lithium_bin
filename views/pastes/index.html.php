<?php
if ($latest->count() == 0):
	echo 'NO PASTES';
	return;
endif;

?>

<table class="pastes">
	<tr>
		<th>Paste</th>
		<th>Preview</th>
		<th>Author</th>
		<th>Date</th>
	</tr>
	<tbody>
	<?php foreach ($latest as $key => $row): ?>
		<tr class="<?=($key % 2) ? 'del1' : 'del2'; ?>">
			<td class="<?=$row->language; ?>" title="<?=$row->language; ?>">
				<?php echo $this->html->link(
					substr($row->id, 0, 12) . '...', '/view/' . $row->id);
				?>
			</td>
			<td class="preview"><?=$row->preview; ?></td>
			<td><?=$row->author; ?></td>
			<td><?php echo date('Y-m-d H:i', strtotime($row->created)); ?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>

<div class="paging">
<?php
	if ($total <= $limit || $page == 1) {
		echo $this->html->tag('span', 'Previous', array('class' => 'prev'));
	} else {
		echo $this->html->link('Previous', array(
			'controller' => 'pastes', 'action' => 'index',
			'page' => $page - 1, 'limit' => $limit
		), array('class' => 'prev'));
	}
	$p = 0; $count = $total;

	while ($count > 0) {
		$p++; $count -= $limit;

		if ($p == $page) {
			echo $this->html->tag('span', $p, array('class' => 'current'));
		} else {
			echo $this->html->link($p, array(
				'controller' => 'pastes', 'action' => 'index',
				'page' => $p, 'limit' => $limit
			));
		}
	}

	if ($total <= $limit || $page == $p) {
		echo $this->html->tag('span', 'Next', array('class' => 'next'));
	} else {
		echo $this->html->link('Next', array(
			'controller' => 'pastes', 'action' => 'index',
			'page' => $page + 1, 'limit' => $limit
		), array('class' => 'next'));
	}
?>
</div>