<?=$this->form->create($paste, array('url' => $url, 'method' => 'POST')); ?>

<?php if (isset($paste->id) && isset($paste->rev)): ?>
	<?=$this->form->hidden('id'); ?>
	<?=$this->form->hidden('rev'); ?>
<?php endif; ?>
<?=$this->form->field('author', array(
	'label' => 'Who are you?'
)); ?>
<?=$this->form->field('remember', array(
	'type' => 'checkbox'
)); ?>
<?=$this->form->field('content', array(
	'type' => 'textarea'
)); ?>
<?=$this->form->field('language', array(
	'options' => $languages
)); ?>
<?=$this->form->field('permanent', array(
	'type' => 'checkbox',
	'label' => 'permanent (shows up in index)'
)); ?>

<?php if ((isset($paste->id) && isset($paste->password) && !empty($paste->password)) || !isset($paste->id)) : ?>
	<?=$this->form->field('password', array(
		'type' => 'password',
		'label' => 'Password (provide one to lock or leave empty if not)'
	)); ?>
<?php endif; ?>
<?php if (isset($paste->id)): ?>
	<?=$this->form->submit('save') ?>
	<?=$this->form->submit('save copy', array('name' => 'copy')); ?>
<?php else: ?>
	<?=$this->form->submit('create') ?>
<?php endif; ?>
</form>