<paste>
	<id><?=$paste->id; ?></id>
<?php if ($paste->author): ?>
	<author><![CDATA[<?php echo$paste->author; ?>]]></author>
<?php endif; ?>
	<created><?php echo $paste->created; ?></created>
	<language><![CDATA[<?php echo $paste->language; ?>]]></language>
	<content><![CDATA[<?=$paste->content; ?>]]></content>
</paste>