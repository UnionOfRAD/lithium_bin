<form method="POST">
<?php if (isset($paste->_id) && isset($paste->_rev)) : ?>
	<input type="hidden" name="Paste[_id]" value="<?=@$paste->_id;?>" />
	<input type="hidden" name="Paste[_rev]" value="<?=@$paste->_rev;?>" />
<?php endif;


echo $this->form->label('Paste.author', 'Who are you?', array(
	'class' => 'required'
));
echo $this->form->text('Paste[author]', array(
	'id' => 'Paste.author',
	'value' => $paste->author
));
if (isset($paste->errors['author'])) {
	echo '<p style="color:red">'.$paste->errors['author'].'</p>';
}
echo $this->form->checkbox('Paste[remember]', array(
	'id' => 'Paste.remember',
	'checked' => 'checked',
));
echo $this->form->label('Paste.remember', ' remember');

echo $this->html->tag('br');
echo $this->html->tag('br');


echo $this->form->label('Paste.content', 'What do you have to say?', array(
	'class' => 'required'
));
echo $this->form->textarea('Paste[content]', array(
	'id' => 'Paste.content',
	'rows' => '20',
	'value' => $paste->content
));
if (isset($paste->errors['content'])) {
	echo '<p style="color:red">'.$paste->errors['content'].'</p>';
}

echo $this->html->tag('br');

echo $this->form->label('Paste.language', 'In what language are you saying this?');
$language_options = array();
foreach ($languages as $l) {
	$language_options[$l] = $l;
}
echo $this->form->select('Paste[language]', $language_options, array(
	'id' => 'Paste.language',
	'value' => $paste->language
));
if (isset($paste->errors['language'])) {
	echo '<p style="color:red">'.$paste->errors['language'].'</p>';
}

echo $this->html->tag('br');

echo $this->form->checkbox('Paste[permanent]', array(
	'id' => 'Paste.permanent'
));
echo $this->form->label('Paste.permanent', " permanent paste");
echo $this->form->submit('save');
?>
</form>