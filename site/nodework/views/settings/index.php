<h1>System Settings</h1>

<div>
	<?= validation_errors() ?>
</div>

<?= form_open('settings') ?>

<div>
	<div>
		<label for="form_server_location">Server Location</label>
	</div>

	<div>
		<?
		$attributes = array(
			'name' => 'server_location',
			'value' => $settings['server_loc'],
			'id' => 'form_server_location'
		);
		echo form_input($attributes);
		?>
	</div>
</div>

<div>
	<div>
		<label for="form_mongodb_location">MongoDB Location</label>
	</div>

	<div>
		<?
		$attributes = array(
			'name' => 'mongodb_location',
			'value' => $settings['mongo_loc'],
			'id' => 'form_mongodb_location'
		);
		echo form_input($attributes);
		?>
	</div>
</div>

<div>
	<div>
		<label for="form_mongodb_port">MongoDB Port</label>
	</div>

	<div>
		<?
		$attributes = array(
			'name' => 'mongodb_port',
			'value' => $settings['mongo_port'],
			'id' => 'form_mongodb_port'
		);
		echo form_input($attributes);
		?>
	</div>
</div>

<div>
	<div>
		<label for="form_mongodb_db">MongoDB Database Name</label>
	</div>

	<div>
		<?
		$attributes = array(
			'name' => 'mongodb_database',
			'value' => $settings['mongo_db'],
			'id' => 'form_mongodb_db'
		);
		echo form_input($attributes);
		?>
	</div>
</div>

<div>
	<?
	$attributes = array(
		'name' => 'submit',
		'value' => 'Update',
		'class' => 'button'
	);
	echo form_submit($attributes);
	?>
</div>

<?= form_close() ?>
