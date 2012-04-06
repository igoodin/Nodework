<h1>My Applications</h1>

<? if(empty($apps)): ?>
<div class="no-items">
	You Don't Have Any Applications
</div>
<? endif; ?>

<? foreach($apps as $app): ?>
<div class="list-item">
	<div class="links">
		<?= anchor(
			"applications/show/{$app['application_id']}",
			'View Statistics',
			array('class' => 'button')
		);
		?>
	</div>
	<div class="summary">
		uniques:&nbsp;<span class="bold"><?= format_stat($app['summary']['uniques']) ?></span> |
		visitors:&nbsp;<span class="bold"><?= format_stat($app['summary']['visitors']) ?></span> |
		requests:&nbsp;<span class="bold"><?= format_stat($app['summary']['requests']) ?></span>
	</div>
	<div class="name">
		<?= anchor(
			"applications/show/{$app['application_id']}",
			$app['application_name'],
			array('class' => 'boring')
		) ?>
		<span class="subname">
			(<?= $app['application_domain'] ?>)
		</span>

	</div>
</div>
<? endforeach; ?>

