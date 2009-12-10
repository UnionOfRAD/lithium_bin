<?php 
echo $this->form->create($paste, array('url' => $url, 'method' => 'POST'));

$errors = $paste->errors();
?>
	<div class="section paste-content">
		<div class="input textarea">
			<textarea name="content" rows="25"><?=$paste->content;?></textarea>
		</div>
		<?php echo (isset($errors['content'])) ?
			'<p class="error">' . implode($errors['content'], '<br>') . '</p>' : null;
		?>
	</div>

	<div class="section paste-meta">

		<?php if (isset($paste->id) && isset($paste->rev)) : ?>
			<input type="hidden" name="id" value="<?=$paste->id;?>" />
			<input type="hidden" name="rev" value="<?=$paste->rev;?>" />
		<?php endif; ?>
		<label for="author">Name/Nick</label>
		<input type="text" name="author" id="author" value="<?=$paste->author;?>" />
		<?php echo (isset($errors['author'])) ?
			'<p class="error">' . implode($errors['author'], '<br>') . '</p>' : null;
		?>
		<div class="checkbox">
		<input type="hidden" name="remember" value="0" />
		<input type="checkbox" id="remember" value="1"
			<?=($paste->remember) ? 'checked=checked' : null;?> name="remember" /> &nbsp;
		<label for="remember">Remember me</label>
		</div>

		<label for="language">Language</label>
		<select id="language" name="language" value="">
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
		<?php echo (isset($errors['language'])) ?
			'<p class="error">' . implode($errors['language'], "<br>") .'</p>' : null;
		?>
		<div class="checkbox">
		<input type="hidden" name="permanent" value="0" />
		<input type="checkbox" id="permanent" value="1"
			<?=($paste->permanent) ? 'checked=checked' : null;?> name="permanent" /> &nbsp;
		<label for="permanent">Save this paste</label>
		</div>
		<?php echo $this->form->submit('Paste this');?>
	</div>
	<div class="notice">
		<small>Pastes are publicly viewable. Paste wisely.</small>
	</div>
</form>