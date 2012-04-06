<h1>Login</h1>

<div><?= validation_errors() ?></div>

<?= form_open('sessions/create') ?>

<div class="field">
	<div class="label">
		<label for="user-email">Email</label>
	</div>
	<div class="value">
	<?
	$attributes = array(
		'name' => 'email',
		'value' => set_value('email'),
		'id' => 'user-email'
	);
	echo form_input($attributes);
	?>
	</div>
</div>

<div class="field">
	<div class="label">
		<label for="user-password">Password</label>
	</div>

	<div class="value">
	<?
	$attributes = array(
		'name' => 'password',
		'value' => '',
		'id' => 'user-password'
	);
	echo form_password($attributes);
	?>
	</div>
</div>

<div class="submit">
<?
$attributes = array(
	'name' => 'submit',
	'value' => 'Login!',
	'class' => 'button'
);
echo form_submit($attributes);
?>
</div>
<?= form_close() ?>
