<a name="snapshot"></a>
<h2 id="toc-start">Snaphot</h2>

<div id="traffic">

<table class="traffic-report" id="traffic-report-snapshot">
	<thead>
	<tr class="large">
		<td class="time-frame">Today</td>
		<td class="traffic-stat">
			<?= format_stat($app_traffic_snapshot['today']['uniques']) ?>
			<br />
			<span class="type">uniques</span>
		</td>
		<td class="traffic-stat">
			<?= format_stat($app_traffic_snapshot['today']['visitors']) ?>
			<br />
			<span class="type">visitors</span>
		</td>
		<td class="traffic-stat">
			<?= format_stat($app_traffic_snapshot['today']['requests']) ?>
			<br />
			<span class="type">requests</span>
		</td>
	</tr>
	</thead>

	<tbody class="hidden">

	<tr class="tier-2">
		<td class="time-frame">Week</td>
		<td class="traffic-stat">
			<?= format_stat($app_traffic_snapshot['week']['uniques']) ?>
			<br />
			<span class="type">uniques</span>
		</td>
		<td class="traffic-stat">
			<?= format_stat($app_traffic_snapshot['week']['visitors']) ?>
			<br />
			<span class="type">visitors</span>
		</td>
		<td class="traffic-stat">
			<?= format_stat($app_traffic_snapshot['week']['requests']) ?>
			<br />
			<span class="type">requests</span>
		</td>
	</tr>
	<tr class="tier-2">
		<td class="time-frame">Month</td>
		<td class="traffic-stat">
			<?= format_stat($app_traffic_snapshot['month']['uniques'])  ?>
			<br />
			<span class="type">uniques</span>
		</td>
		<td class="traffic-stat">
			<?= format_stat($app_traffic_snapshot['month']['visitors']) ?>
			<br />
			<span class="type">visitors</span>
		</td>
		<td class="traffic-stat">
			<?= format_stat($app_traffic_snapshot['month']['requests']) ?>
			<br />
			<span class="type">requests</span>
		</td>
	</tr>
	</tbody>
</table>

<div id="unhide-months">
	<button id="button-show-month-week" type="button">Show Week and Month Data</button>
</div>

<div id="hide-months">
	<button id="button-hide-month-week" class="hidden" type="button">Hide Week and Month Data</button>
</div>
</div>
