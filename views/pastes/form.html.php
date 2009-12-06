<?php 
echo $this->form->create($paste, array('method' => 'POST'));

$errors = $paste->errors();

$this->form->config(array('templates' => array('checkbox' =>
	'<input type="hidden" name="{:name}" value="0" />
	 <input type="checkbox" value="1" name="{:name}"{:options} />'
)));

if (isset($paste->id) && isset($paste->rev)) {
		echo $this->form->hidden('id');
		echo $this->form->hidden('rev');
}

echo $this->form->label('Paste.author', 'Who are you?', array('class' => 'required'));
echo $this->form->text('author', array('id' => 'Paste.author'));
if (isset($errors['author'])) {
	echo '<p style="color:red">'.$errors['author'].'</p>';
}

echo $this->form->checkbox('remember', array('id' => 'Paste.remember'));
echo $this->form->label('Paste.remember', ' remember');
?>
<br><br>
<?php

echo $this->form->label('Paste.content', 'Paste content', array('class' => 'required'));
echo $this->form->textarea('content', array('id' => 'Paste.content', 'rows' => '20'));
if (isset($errors['content'])) {
	echo '<p style="color:red">'.$errors['content'].'</p>';
}

?>
<br>
<?php

echo $this->form->label('Paste.language', 'language');
echo $this->form->select('language', array_combine($languages, $languages), array(
	'id' => 'Paste.language'
));
if (isset($errors['language'])) {
	echo '<p style="color:red">'.$errors['language'].'</p>';
}

echo $this->form->checkbox('permanent', array('id' => 'Paste.permanent'));
echo $this->form->label('Paste.permanent', " permanent");

?>
<br><br>
<?php echo $this->form->submit('save');?>
</form>