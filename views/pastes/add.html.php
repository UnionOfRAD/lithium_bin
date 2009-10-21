<pre><code><?php
//print_r($paste);
?></code></pre>
<form method="POST">
	<label for="Paste[author]">Who are you?</label>
	<input type="text" name="Paste[author]" value="<?=@$paste->author;?>" />
	<?=@(isset($paste->errors['author'])) ?
		'<p style="color:red">'.$paste->errors['author'].'</p>' : null;
	?>
	<input type="hidden" name="Paste[remember]" value="0" />
	<input type="checkbox" id="Paste.remember" value="1"
		<?=($paste->remember) ? 'checked=checked' : null;?> name="Paste[remember]" /> &nbsp;
	<label for="Paste.remember">remember me</label>
	<?=@(isset($paste->errors['remember'])) ?
		'<p style="color:red">'.$paste->errors['remember'].'</p>' : null;
	?>

	<br><br>

	<label for="Paste[content]" class="required">What do you have to say?</label>
	<textarea name="Paste[content]" rows="15"><?=@$paste->content;?></textarea>

	<?=@(isset($paste->errors['content'])) ?
		'<p style="color:red">'.$paste->errors['content'].'</p>' : null;
	?>

	<br>

	<label for="Paste.language">In what language are you saying this?</label>
	<select id="Paste.language" name="Paste[language]" value="">
	<?php
		foreach ($languages as $lang) {
			if ($lang == $paste->language) {
				echo "<option selected='selected' value='{$lang}'>{$lang}</option>";
			} else {
				echo "<option value='{$lang}'>{$lang}</option>";
			}
		}
	?>
	</select>
	<?=@(isset($paste->errors['language'])) ?
		'<p style="color:red">'.$paste->errors['language'].'</p>' : null;
	?>

	<br>

	<input type="hidden" name="Paste[permanent]" value="0" />
	<input type="checkbox" id="Paste.permanent" value="1"
		<?=($paste->permanent) ? 'checked=checked' : null;?> name="Paste[permanent]" /> &nbsp;
	<label for="Paste.permanent">save</label>
	<?=@(isset($paste->errors['permanent'])) ?
		'<p style="color:red">'.$paste->errors['permanent'].'</p>' : null;
	?>

	<br><br>

	<input type="submit" />
</form>