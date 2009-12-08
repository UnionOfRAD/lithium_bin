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

<ul id="actions">
	<li><?php
		if ($total <= $limit || $page == 1) {
			echo '<<-First</li><li><-Previous';
		} else {
			echo $this->html->link('<<-First', array(
				'controller' => 'pastes', 'action' => 'index',
				'page' => 1, 'limit' => $limit
			));
			echo '</li><li>';
			echo $this->html->link('<-Previous', array(
				'controller' => 'pastes', 'action' => 'index',
				 'page' => $page - 1, 'limit' => $limit
			));

		} ?>
	</li>
	<?php

	$p = 0; $count = $total;
	while ($count > 0) {
		$p++; $count -= $limit;
		echo '<li>';
		if ($p == $page) {
			echo '['.$p.']';
		} else {
			echo $this->html->link('['.$p.']', array(
				'controller' => 'pastes', 'action' => 'index',
				'page' => $p, 'limit' => $limit
			));
		}
		echo '</li>';
	}
	?>
	<li><?php
		if ($total <= $limit || $page == $p) {
			echo 'Next-></li><li>Last->>';
		} else {
			echo $this->html->link('Next->', array(
				'controller' => 'pastes', 'action' => 'index',
				'page' => $page + 1, 'limit' => $limit
			));
			echo '</li><li>';
			echo $this->html->link('Last->>', array(
				'controller' => 'pastes', 'action' => 'index',
				'page' => $total, 'limit' => $limit
			));
		}?>
	</li>
</ul>