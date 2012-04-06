<div id="showone">

<input type="hidden" id="app_id" value="<?= $app['application_id'] ?>" />

<div>
	<h1>
		<?= $app['application_name'] ?>
		<span class="subtitle">(<?= $app['application_domain']?>)</span>
	</h1>
</div>

<?
	/* Load the administration panel */
	echo $this->partial->build(
		'applications/partials/showone_admin',
		array(
			'app_perms' => $app_perms
		)
	)
?>

<?
	/* Load the table of contents */
	echo $this->partial->build(
		'applications/partials/showone_toc'
		);
?>

<?
	/* Load snapshot data */
	echo $this->partial->build(
		'applications/partials/showone_snapshot',
		array(
			'app_traffic_snapshot' => $app_traffic_snapshot
		)
	);
?>

<?
	/* Load trends (traffic chart, top pages & referals, charts + extras) */
	echo $this->partial->build(
		'applications/partials/showone_trends',
		array(
			'set_start_filter' => $set_start_filter,
			'set_end_filter' => $set_end_filter,
			'date_filter_start' => $date_filter_start,
			'date_filter_end' => $date_filter_end,
			'app' => $app,
			'app_traffic_range' => $app_traffic_range,
			'traffic_chart' => $traffic_chart,
			'pages' => $pages,
			'referrals' => $referrals,
			'browsers' => $browsers,
			'platforms' => $platforms,
			'mobiles' => $mobiles,
			'resolutions' => $resolutions
		)
	);
?>

<?
	/* Load actual trends (historical trends, browser & platform) */
	echo $this->partial->build(
		'applications/partials/showone_actual',
		array(
			'browser_trends' => $browser_trends,
			'platform_trends' => $platform_trends
		)
	);
?>

<?
	/* Load time/day traffic data */
	echo $this->partial->build(
		'applications/partials/showone_times',
		array(
			'times_len' => $times_len,
			'times' => $times
		)
	);
?>

<?
	/* Load geolocation data */
	echo $this->partial->build(
		'applications/partials/showone_geo',
		array(
			'loc' => $loc
		)
	);
?>

<div id="blank-content" class="hidden ui-corner-all">
	<div id="bc-inner" class="ui-corner-all">
		<div id="bc-content">
		</div>
		<div>
			<button type="button" id="close-bc" class="ui-button">
				Close
			</button>
		</div>
	</div>
</div>

<?= $this->partial->build('applications/partials/showone_js') ?>

</div>
