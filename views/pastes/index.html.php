<h2>Latest</h2>
<?php
if ($latest == null):
	echo 'NO PASTES';
	return;
endif;

$total = $latest->total(); 

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
<ul id="actions">
	<li><?php 
		if ($total <= $limit || $page == 1) {
			echo '<<-First';
		} else {
			echo $this->html->link('<<-First', array('action' => 'index', 'args' => array('page:1','limit:'.$limit)));
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
			echo $this->html->link('['.$p.']', array('action' => 'index', 'args' => array('page:'.$p,'limit:'.$limit)));
		}
		echo '</li>';
	}
	?>	
	<li><?php 
		if ($total <= $limit || $page == $p) {
			echo 'Last->>';
		} else {
			echo $this->html->link('Last->>', array('action' => 'index', 'args' => array('page:'.$p,'limit:'.$limit))); 
		}?>
	</li>
</ul>