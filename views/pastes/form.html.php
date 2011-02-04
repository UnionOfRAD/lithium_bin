<?=$this->form->create($paste, compact('url') + array('method' => 'POST')); ?>

<?php if (isset($paste->id) && isset($paste->rev)): ?>
	<?=$this->form->hidden('id'); ?>
	<?=$this->form->hidden('rev'); ?>
<?php endif; ?>

<div class="section paste-content">
	<?=$this->form->field('content', array(
		'type' => 'textarea'
	)); ?>
</div>
<div class="section paste-meta">
	<?=$this->form->field('author', array(
		'label' => 'Author'
	)); ?>
	<?=$this->form->field('remember', array(
		'label' => 'remember me',
		'type' => 'checkbox'
	)); ?>
	<?=$this->form->field('language', array(
		'type' => 'select',
		'list' => array_combine($languages, $languages)
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
</div>
<?php if (isset($paste->id)): ?>
	<?=$this->form->submit('save') ?>
	<?=$this->form->submit('save copy', array('name' => 'copy')); ?>
<?php else: ?>
	<?=$this->form->submit('create') ?>
<?php endif; ?>
<div class="notice">
	<small>Pastes are publicly viewable. Paste wisely.</small>
</div>
<?=$this->form->end(); ?>