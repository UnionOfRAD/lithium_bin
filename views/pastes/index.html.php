<h2>Latest</h2>
<?php
if ($latest == null):
	echo 'NO PASTES';
	return;
endif;
?>
<ul class="latest">
	<?php foreach($latest->rows as $row): ?>
		<li>
			<?=$row->value->author?> @
			<?=$row->value->created?> &middot;
			<?=$row->value->language?>
			<?=@$this->html->link('view', '/view/' . $row->id)?>
			<p><?=$row->value->preview?></p>
		</li>
	<?php endforeach;?>
</ul>
