<table class="pastes">
	<tr>
		<th>Paste</th>
		<th>Preview</th>
		<th>Author</th>
		<th>Date</th>
	</tr>
	<tbody>
	<?php foreach ($latest->rows as $key => $row): ?>
		<tr class="<?=($key % 2) ? 'del1' : 'del2'; ?>">
			<td class="<?=$row->value->language; ?>" title="<?=$row->value->language; ?>">
				<?php echo $this->html->link(substr($row->id, 0, 12) . '...', '/view/'.$row->id); ?>
			</td>
			<td class="preview"><?=$row->value->preview; ?></td>
			<td><?=$row->value->author; ?></td>
			<td><?php echo date('Y-m-d H:i', strtotime($row->value->created)); ?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>