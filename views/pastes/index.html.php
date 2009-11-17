<h2>Latest</h2>
<?php
if ($latest == null):
	echo 'NO PASTES';
	return;
endif;
?>
<ul class="latest">
	<?php foreach($latest as $row): ?>
		<li>
			<?=$row->author?> @
			<?=$row->created?> &middot;
			<?=$row->language?>
			<?php echo $this->html->link('view', '/view/' . $row->id)?>
			<p><?=$row->preview?></p>
		</li>
	<?php endforeach;?>
</ul>
