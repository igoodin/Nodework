<div class="label bold underline">Top Pages</div>

<table class="display">
	<tr>
		<? if(sizeof($pages) > 0): ?>
		<th>Page</th>
		<th>Percent</th>
		<? else: ?>
		<th>No</th>
		<th>Data</th>
		<? endif; ?>
	<tr>
<? foreach($pages as $page): ?>
	<tr>
		<td>
			<span title="<?= $page['fullpage'] ?>">
				<?= $page['page'] ?>
			<a href="<?= site_url('heatmap/build?page='.urlencode($page['fullpage'])) ?>" target="_blank">(heatmap)</a>
			</span>
		</td>
		<td><?= $page['pct'] ?>%</td>
	</tr>
<? endforeach; ?>
</table>
