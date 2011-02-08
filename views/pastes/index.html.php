<?php
$truncate = function($string, $length = 50) {
	return strlen($string) > $length ? substr($string, 0, $length) . '…' : $string;
};
$preview = function($string) use ($truncate) {
	$contents = explode("\n", $string);
	$offset = (integer) (count($contents) / 3.5);

	foreach ($contents as $key => $line) {
		if (strpos($line, 'class') !== false) {
			$offset = $key;
			break;
		}
	}
	$preview = array_slice($contents, $offset, 2);
	$preview = array_map($truncate, $preview);

	return implode("\n", $preview);
};
$nickRgb = function($nick) {
	$hash = abs(crc32($nick));

	$rgb = array($hash % 255, $hash % 255, $hash % 255);
	$rgb[$hash % 2] = 0;

	return $rgb;
};
?>
<h2><?=$this->title('Latest Pastes'); ?></h2>
<?php if ($latest->count()): ?>
<table class="pastes">
	<tr>
		<th>Id</th>
		<th>Preview</th>
		<th>Author</th>
		<th>Created</th>
	</tr>
	<tbody>
	<?php foreach ($latest as $key => $row): ?>
		<tr class="<?=($key % 2) ? 'del1' : 'del2'; ?>">
			<td class="<?=$row->language; ?>" title="<?=$row->language; ?>">
				<?php echo $this->html->link(
					$truncate($row->id, 12), '/view/' . $row->id);
				?>
			</td>
			<td class="preview"><?= $preview($row->content); ?></td>
			<td style="color: rgb(<?=implode(',' , $nickRgb($row->author))?>);"><?=$row->author; ?></td>
			<td><time datetime="<?=date('c', strtotime($row->created)); ?>"><?=$row->created; ?></time></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>
<?php else: ?>
	<p class="none-available">No pastes available.</p>
<?php endif; ?>