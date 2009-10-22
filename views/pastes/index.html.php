<h2>Latest Pastes</h2>

<table class="pastes">
	<tbody>
		<tr>
			<th>Paste</th>
			<th>Preview</th>
			<th>Author</th>
			<th>Date</th>
		</tr>
<?php
	foreach ($latest->rows as $key => $row) { ?>
		<tr class="<?php echo ($key % 2) ? 'del1' : 'del2'; ?>">
			<td class="<?php echo $row->value->language; ?>" title="<?php echo $row->value->language; ?>"><?php echo $this->html->link(substr($row->id, 0, 16).'...', '/view/'.$row->id); ?></td>
			<td class="preview"><?php echo htmlspecialchars(urldecode($row->value->preview)); ?></td>
			<td><?php echo $row->value->author; ?></td>
			<td><?php echo date('Y-m-d', strtotime($row->value->created)); ?></td>
		</tr>
<?php
	}
?>
	</tbody>
</table>
