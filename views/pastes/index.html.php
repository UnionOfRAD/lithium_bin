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
<ul id="actions">
	<li><?php 
		if ($total <= $limit || $page == 1) {
			echo '<<-First</li><li><-Previous';
		} else {
			echo $this->html->link('<<-First', array('action' => 'index', 'args' => array('page:1','limit:'.$limit)));
			echo '</li><li>';
			echo $this->html->link('<-Previous', array('action' => 'index', 'args' => array('page:'.($page-1),'limit:'.$limit)));
		
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
			echo 'Next-></li><li>Last->>';
		} else {
			echo $this->html->link('Next->', array('action' => 'index', 'args' => array('page:'.($page+1),'limit:'.$limit))); 
			echo '</li><li>';
			echo $this->html->link('Last->>', array('action' => 'index', 'args' => array('page:'.$p,'limit:'.$limit))); 
		}?>
	</li>
</ul>