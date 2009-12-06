<form method="POST">

	<div class="section paste-content">
		<div class="input textarea">
			<textarea name="content" rows="25"><?=$paste->content;?></textarea>
		</div>
		<?php echo (isset($paste->errors['content'])) ?
			'<p class="error">'.$paste->errors['content'].'</p>' : null;
		?>
	</div>

	<div class="section paste-meta">

		<?php if (isset($paste->id) && isset($paste->rev)) : ?>
			<input type="hidden" name="id" value="<?=$paste->id;?>" />
			<input type="hidden" name="rev" value="<?=$paste->rev;?>" />
		<?php endif; ?>
		<label for="Paste.author">Name/Nick</label>
		<input type="text" name="author" id="Paste.author" value="<?=$paste->author;?>" />
		<?php echo (isset($paste->errors['author'])) ?
			'<p class="error">'.$paste->errors['author'].'</p>' : null;
		?>
		<div class="checkbox">
		<input type="hidden" name="remember" value="0" />
		<input type="checkbox" id="Paste.remember" value="1"
			<?=($paste->remember) ? 'checked=checked' : null;?> name="remember" /> &nbsp;
		<label for="Paste.remember">Remember me</label>
		</div>

		<label for="Paste.language">Language</label>
		<select id="Paste.language" name="language" value="">
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
		<?php echo (isset($paste->errors['language'])) ?
			'<p class="error">'.$paste->errors['language'].'</p>' : null;
		?>
		<div class="checkbox">
		<input type="hidden" name="permanent" value="0" />
		<input type="checkbox" id="Paste.permanent" value="1"
			<?=($paste->permanent) ? 'checked=checked' : null;?> name="permanent" /> &nbsp;
		<label for="Paste.permanent">Save this paste</label>
		</div>
		<?php echo $this->form->submit('Paste this');?>
	</div>
	<div class="notice">
		<small>Pastes are publicly viewable. Paste wisely.</small>
	</div>
</form>