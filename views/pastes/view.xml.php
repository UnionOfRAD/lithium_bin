<paste>
	<id><?=$paste->id; ?></id>
<?php if ($paste->author): ?>
	<author><?=$paste->author; ?></author>
<?php endif; ?>
	<created><?=$paste->created; ?></created>
	<language><?=$paste->language; ?></language>
	<content><?=$paste->content; ?></content>
</paste>