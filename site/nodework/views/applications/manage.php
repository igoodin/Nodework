<h1>Applications</h1>

<? if(empty($apps)): ?>
<div class="no-items">
	There Are Currently No Applications
</div>
<? endif; ?>

<? foreach($apps as $app): ?>
<div class="list-item">
	<div class="links">
		<?= anchor(
			"applications/show/{$app['application_id']}",
			'Show',
			array('class' => 'button')
		)?>
		<?= anchor(
			"applications/update/{$app['application_id']}",
			'Update',
			array('class' => 'button')
		)?>
		<?= anchor(
			"applications/manage/{$app['application_id']}",
			'Manage Users',
			array('class' => 'button')
		)?>
	</div>

	<div class="name">
		<?= $app['application_name'] ?>
		<span class="subname">
			(<?= $app['application_domain'] ?>)
		</span>
	</div>
</div>
<? endforeach; ?>

