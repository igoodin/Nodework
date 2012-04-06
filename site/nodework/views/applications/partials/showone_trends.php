<div id="trends-panel">

<a name="trends"></a>
<h2 id="trends">
	Trends -

	<? if(! $set_start_filter && ! $set_end_filter): ?>
		<span id="date-range">
			Last 14 days
		</span>
	<? else: ?>
		<span id="date-range" class="highlight">
			<?= strftime('%m/%d/%Y', $date_filter_start) ?> -
			<?= strftime('%m/%d/%Y', $date_filter_end) ?>
		</span>
	<? endif; ?>

	<span id="change-form-span" class="hidden">
		<form id="change-form" action=<?= site_url("applications/show/{$app['application_id']}") ?> method="get">
			<label for="start-date">Start Date:</label>
			<?
			$start_val = !$date_filter_start ? '' : strftime('%m/%d/%Y', $date_filter_start);
			$end_val = !$date_filter_end ? '' : strftime('%m/%d/%Y', $date_filter_end);
			?>
			<input type="text" name="start_date" id="start-date" value="<?= $start_val ?>"/>

			<label for="end-date">End Date:</label>
			<input type="text" name="end_date" id="end-date" value="<?= $end_val ?>" />

			<button name="s" type="submit">Apply Filter</button>
		</form>
	</span>

	<? if($set_start_filter || $set_end_filter): ?>
		<?= anchor("applications/show/{$app['application_id']}", 'reset') ?>
	<? endif; ?>

	<span id="change-range-open" class="change-range">
		<a class="fakelink">change</a>
	</span>
	<span id="change-range-close" class="change-range hidden">
		<a class="fakelink">cancel</a>
	</span>
</h2>

<a name="trends-traffic"></a>

<? if($set_start_filter || $set_end_filter): ?>
<div class="section-header">Traffic Metrics</div>

<div id="traffic">
	<table class="traffic-report">
		<tr class="tier-2">
			<td class="time-frame">Traffic</td>

			<td class="traffic-stat">
				<?= $app_traffic_range['uniques'] ?><br />
				<span class="type">uniques</span>
			</td>

			<td class="traffic-stat">
				<?= $app_traffic_range['visitors'] ?><br />
				<span class="type">visitors</span>
			</td>

			<td class="traffic-stat">
				<?= $app_traffic_range['requests'] ?><br />
				<span class="type">requests</span>
			</td>
		</tr>
	</table>
</div>
<? endif; ?>

<div class="section-header">Traffic Chart</div>

<div id="traffic-img">
	<div class="value" id="traffic-chart-div">
		<?
		$traffic_size = sizeof($traffic_chart);
		if($traffic_size > 2):
		?>
		<table id="traffic-chart" class="data">
			<thead>
				<tr>
				<td></td>
				<?
				$step = intval($traffic_size / 3);
				$last = 0;
				?>
				<? for($i = 0; $i < $traffic_size; $i++): ?>
					<? $day = $traffic_chart[$i]; ?>
					<? if($i == $last): ?>
						<?if(isset($day['date'])):?>
							<? $val = strftime("%m/%d/%Y", $day['date']) ?>
							
						<?else:?>
							<? $val =strftime("%m/%d/%Y", $date_filter_start) ?>
						<?endif;?>
						<? $last += $step; ?>
					<? else: ?>
						<? $val = ''; ?>
					<? endif; ?>
					<th scope="column"><?= $val ?></th>
				<? endfor; ?>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th scope="row">Requests</th>
					<? foreach($traffic_chart as $day): ?>
					<td><?= $day['requests']?></td>
					<? endforeach; ?>
				</tr>

				<tr>
					<th scope="row">Uniques</th>
					<? foreach($traffic_chart as $day): ?>
					<td><?= $day['uniques']?></td>
					<? endforeach; ?>
				</tr>

				<tr>
					<th scope="row">Visitors</th>
					<? foreach($traffic_chart as $day): ?>
					<td><?= $day['visitors']?></td>
					<? endforeach; ?>
				</tr>
			</tbody>
		</table>
		<? else: ?>
			<div id="no-traffic">
				<?= img("nodework/views/static/images/linechartnodata.png") ?>
			</div>
		<? endif; ?>
	</div>
</div>

<div style="clear: both"></div>

<a name="trends-pages-referrals"></a>

<div class="section-header">Top Pages &amp; Referals</div>

<div id="pages-pane">
	<span class="pane top-pages inline-block">
		<div class="label bold underline">Top Pages</div>
		<div class="value">
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
					<td class="table-left">
						<span title="<?= $page['fullpage'] ?>">
							<?= $page['page'] ?>
						<a href="<?= site_url('heatmap/build?page='.urlencode($page['fullpage'])."&start_date=$date_filter_start&end_date=$date_filter_end") ?>" target="_blank">(clickmap)</a>
						</span>
					</td>
					<td><?= $page['pct'] ?>%</td>
				</tr>
			<? endforeach; ?>
			<? $sap_button_disabled = ''; ?>
			<? if(sizeof($pages) < 10): ?>
				<? $sap_button_disabled = 'disabled'; ?>
				<? for($i = 0; $i < (10 - sizeof($pages)); $i++): ?>
				<tr>
					<td>&nbsp;</td>
					<td></td>
				</tr>
				<? endfor; ?>
			<? endif; ?>
				<tr>
					<td>
						<button type="button" id="show-all-pages" <?= $sap_button_disabled ?>>
							Show All Pages
						</button>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
	</span>

	<span class="top-referrals inline-block">
		<div class="label bold underline">Top Referrals</div>
		<div class="value">
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
						<a href="<?= 'http://'.$referrer['ref'] ?>">
							<?= $referrer['ref'] ?>
						</a>
					<? else: ?>
						Typed URL In
					<? endif; ?>
					</td>
					<td><?= $referrer['pct'] ?>%</td>
				</tr>
			<? endforeach; ?>
			<? $sar_button_disabled = ''; ?>
			<? if(sizeof($referrals) < 10): ?>
				<? $sar_button_disabled = 'disabled'; ?>
				<? for($i = 0; $i < (10 - sizeof($referrals)); $i++): ?>
				<tr>
					<td>&nbsp;</td>
					<td></td>
				</tr>
				<? endfor; ?>
			<? endif; ?>
			<tr>
				<td>
					<button type="button" id="show-all-referrers" <?= $sar_button_disabled ?>>
						Show All Refferals
					</button>
				</td>
				<td></td>
			</tr>
			</table>
		</div>
	</span>
</div>

<a name="trends-charts"></a>

<div class="section-header">Charts &amp; Extras</div>

<div id="bpr-pane">
	<span class="pane browsers inline-block">
		<div class="label bold underline">
			Browsers
		</div>
		<div class="value">
			<? if(sizeof($browsers ) > 0): ?>
				<table class="data" id="browser-chart">
					<thead>
					<tr>
					<? foreach($browsers as $key=>$value): ?>
						<td></td>
						<th scope="col"><?= $key ?></th>
					<? endforeach; ?>
					</tr>
					</thead>

					<tbody>
					<? foreach($browsers as $key=>$value): ?>
						<tr>
							<th scope="row"><?= $key ?></th>
							<td><?= $value ?></td>
						</tr>
					<? endforeach; ?>
					</tbody>
				</table>
			<? else: ?>
				<?= img("nodework/views/static/images/browsers.png") ?>
			<? endif; ?>

		</div>
	</span>

	<span class="platforms inline-block">
		<div class="label bold underline">
			Platforms
		</div>
		<div class="value" id="platform-chart">
			<? if(sizeof($platforms) > 0): ?>
				<table class="data" id="platform-chart">
					<thead>
					<tr>
					<? foreach($platforms as $key=>$value): ?>
						<td></td>
						<th scope="col"><?= $key ?></th>
					<? endforeach; ?>
					</tr>
					</thead>

					<tbody>
					<? foreach($platforms as $key => $value): ?>
					<tr>
						<th scope="row"><?= $key ?></th>
						<td><?= $value ?></td>
					</tr>
					<? endforeach; ?>
					</tbody>
				</table>
			<? else: ?>
				<?= img("nodework/views/static/images/platforms.png") ?>
			<? endif; ?>
		</div>
	</span>

	<span class="pane mobiles inline-block">
		<div class="label bold underline">
			Mobile Platforms
		</div>
		<div class="value" id="mobile-chart">
			<? if(sizeof($mobiles) > 0): ?>
				<table class="data" id="mobile-chart">
					<thead>
					<tr>
					<? foreach($mobiles as $key=>$value): ?>
					<td></td>
						<th scope="col"><?= $key ?></th>
					<? endforeach; ?>
					</tr>
					</thead>

					<tbody>
					<? foreach($mobiles as $key => $value): ?>
					<tr>
						<th scope="row"><?= $key ?></th>
						<td><?= $value ?></td>
					</tr>
					<? endforeach; ?>
					</tbody>
				</table>
			<? else: ?>
				<?= img("nodework/views/static/images/mobileplatforms.png") ?>
			<? endif; ?>
		</div>
	</span>

	<span class="resolutions inline-block">
		<div class="label bold underline">
			Screen Resolutions
		</div>
		<div class="value inline-block">
			<? if(sizeof($resolutions) > 0): ?>
				<table class="data" id="resolutions-table">
					<thead>
						<tr>
						<td></td>
						<th scope="col">Resolutions</th>
						</tr>
					</thead>
					<tbody>
						<? foreach($resolutions as $res): ?>
							<tr>
							<th scope="row"><?= $res['res'] ?></th>
							<td><?= intval($res['percent']) ?></td>
							</tr>
						<? endforeach; ?>
					</tbody>
				</table>
			<? else: ?>
				<?= img("nodework/views/static/images/resolutions.png") ?>
			<? endif; ?>
		</div>
	</span>
</div>

</div>
