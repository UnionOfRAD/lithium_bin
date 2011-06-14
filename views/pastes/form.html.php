<h2><?=$this->title($this->_request->action == 'add' ? 'New Paste' : 'Edit Paste'); ?></h2>

<?=$this->form->create($paste, compact('url') + array('method' => 'POST')); ?>

<?php if (isset($paste->id) && isset($paste->rev)): ?>
	<?=$this->form->hidden('id'); ?>
	<?=$this->form->hidden('rev'); ?>
<?php endif; ?>

<div class="section paste-content">
	<?=$this->form->field('content', array(
		'type' => 'textarea'
	)); ?>
	<div class="notice">
		Pastes are publicly viewable.
		Pastium is <?=$this->html->link(
			'open source',
			'https://github.com/UnionOfRAD/lithium_bin'
		); ?>.
	</div>
</div>
<div class="section paste-meta">
	<div class="fields">
	<?=$this->form->field('author', array(
		'label' => 'Author'
	)); ?>
	<?php
		/* Promote most used languages to the top. */
		$languages = array(
			'bash',
			'css',
			'diff',
			'gettext',
			'php',
			'powershell',
			'ini',
			'javascript',
			'sql',
			'mysql',
			'text',
			'xml',
			'-------------'
		) + $languages;
	?>
	<?=$this->form->field('language', array(
		'type' => 'select',
		'list' => array_combine($languages, $languages)
	)); ?>
	<?=$this->form->field('remember', array(
		'label' => 'remember',
		'type' => 'checkbox',
		'checked' => true
	)); ?>
	<span class="help">Store last used settings.</span>
	<?=$this->form->field('private', array(
		'type' => 'checkbox',
		'label' => 'private'
	)); ?>
	<span class="help">Will need URL to view paste.</span>

	<?php if ((isset($paste->id) && !empty($paste->immutable)) || !isset($paste->id)) : ?>
		<?=$this->form->field('immutable', array(
			'type' => 'checkbox',
			'label' => 'immutable'
		)); ?>
		<span class="help">Prevents editing of paste.</span>
	<?php endif; ?>

	<!-- Catch Me If You Can -->
	<?=$this->form->text('catch', array(
		'style' => 'position:absolute; margin-left: -5000px;'
	)); ?>
	</div>
	<div class="controls">
	<?php if (isset($paste->id)): ?>
		<?=$this->form->submit('save') ?>
		<?=$this->form->submit('save as copy', array('name' => 'copy')); ?>
	<?php else: ?>
		<?=$this->form->submit('paste this') ?>
	<?php endif; ?>
	</div>

</div>
<div style="clear:both;"></div>
<?=$this->form->end(); ?>