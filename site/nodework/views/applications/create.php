<h1>Track an Application</h1>

<?= form_open('applications/create') ?>

<div>
	<?= validation_errors() ?>
</div>

<div>
	<div>
		<label for="application_name">Name</label>
	</div>

	<div>
		<?
		$attributes = array(
			'name' => 'name',
			'id' => 'application_name',
			'value' => set_value('name')
		);
		echo form_input($attributes);
		?>
	</div>
</div>


<div>
	<div>
		<label for="application_domain">Domain</label>
	</div>

	<div>
		<?
		$attributes = array(
			'name' => 'domain',
			'id' => 'application_domain',
			'value' => set_value('domain')
		);
		echo form_input($attributes);
		?>
	</div>
</div>

<div>
	<div>
		<label for="application_params">Exclude Url parameters (comma seperated)</label>
	</div>

	<div>
		<?
		$attributes = array(
			'name' => 'params',
			'id' => 'application_params',
			'value' => set_value('params')
		);
		echo form_input($attributes);
		?>
	</div>
</div>

<div>
	<div>
		<label for="application_align">Page Alignment</label>
	</div>

	<div>
		<input type="radio" name="align" value="noscale" <?=set_radio('align','noscale',TRUE);?>/>
		<label for="application_align">Left</label>
		<input type="radio" name="align" value="scale" <?=set_radio('align','scale',FALSE);?>/>
		<label for="application_align">Center</label>
	</div>
</div>


<div>
	<?
	$attributes = array(
		'name' => 'submit',
		'value' => 'Create!',
		'class' => 'button'
	);
	echo form_submit($attributes);
	?>
</div>

<?= form_close() ?>
