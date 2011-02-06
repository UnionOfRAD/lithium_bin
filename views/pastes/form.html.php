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
	<?=$this->form->field('language', array(
		'type' => 'select',
		'list' => array_combine($languages, $languages)
	)); ?>
	<?=$this->form->field('remember', array(
		'label' => 'remember me',
		'type' => 'checkbox'
	)); ?>
	<?=$this->form->field('private', array(
		'type' => 'checkbox',
		'label' => 'private'
	)); ?>
	<span class="help">Causes the paste to not show up in any public index.</span>

	<?php if ((isset($paste->id) && !empty($paste->immutable)) || !isset($paste->id)) : ?>
		<?=$this->form->field('immutable', array(
			'type' => 'checkbox',
			'label' => 'immutable'
		)); ?>
		<span class="help">Prevents editing of paste.</span>
	<?php endif; ?>
</div>

<!-- Catch Me If You Can -->
<?=$this->form->text('catch', array(
	'style' => 'position:absolute; margin-left: -5000px;'
)); ?>

<?php if (isset($paste->id)): ?>
	<?=$this->form->submit('save') ?>
	<?=$this->form->submit('save as copy', array('name' => 'copy')); ?>
<?php else: ?>
	<?=$this->form->submit('paste this') ?>
<?php endif; ?>
<div class="notice">Pastes are publicly viewable. Paste wisely.</div>
<?=$this->form->end(); ?>