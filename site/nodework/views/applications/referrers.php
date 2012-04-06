<div class="label bold underline">Top Referrals</div>

<table class="display">
	<tr>
		<? if(sizeof($referrals) > 0): ?>
		<th>Referrer</th>
		<th>Percent</th>
		<? else: ?>
		<th>No</th>
		<th>Data</th>
		<? endif; ?>
	</tr>
<? foreach($referrals as $referrer): ?>
	<tr>
		<td class="table-left">
		<? if(! empty($referrer['ref'])): ?>
			<a href="<?= $referrer['ref'] ?>">
				<?= $referrer['ref'] ?>
			</a>
		<? else: ?>
			Typed URL In
		<? endif; ?>
		</td>
		<td><?= $referrer['pct'] ?>%</td>
	</tr>
<? endforeach; ?>
</table>
