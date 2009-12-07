<h2>Install</h2>
<br>
<?php
 
 if (isset($view->reason) && $view->reason == 'no_db_file') {
 	echo '<h2 style="color:red;">';
 	echo 'Database does not exist. Please make sure CouchDB is running and refresh to try again.';
 	echo '</h2>';
 	echo $this->html->link('REFRESH',array('action' => 'install'));
 } elseif (isset($view->reason) && $view->reason == 'missing') {
 	echo '<h2 style="color:red;">';
 	echo 'Database exist, but design views not. Please refresh to try again.';
 	echo '</h2>';
 	echo $this->html->link('REFRESH',array('action' => 'install'));
 } elseif(isset($view->id) && $view->id == '_design/paste' && count($view->views) == 2)  {
 	echo '<h2 style="color:green;">';
 	echo 'Everything is OK.';
 	echo '</h2>';
 	echo $this->html->link('Add a paste',array('action' => 'add'));
 }
 
 
 ?>