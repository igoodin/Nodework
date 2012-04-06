<? if(is_ldap()): ?>
	Sorry you can't do this.
<? else: ?>
	<h1>Change Password</h1>

	<div>
		<?= validation_errors() ?>
	</div>
	<?= form_open('users/profile') ?>
	<div class="field">
		<div class="label">
			<label for="user-current-password">Current Password</label>
		</div>

		<div class="value">
		<?
		$attributes = array(
			'name' => 'cur_password',
			'value' => '',
			'id' => 'user-current-password'
		);
		echo form_password($attributes);
		?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-new-password">New Password</label>
		</div>

		<div class="value">
		<?
		$attributes = array(
			'name' => 'new_password',
			'value' => '',
			'id' => 'user-new-password'
		);
		echo form_password($attributes);
		?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-confirm-password">New Password (confirm)</label>
		</div>

		<div class="value">
		<?
		$attributes = array(
			'name' => 'confirm_password',
			'value' => '',
			'id' => 'user-confirm-password'
		);
		echo form_password($attributes);
		?>
		</div>
	</div>

	<div class="submit">
	<?
	$attributes = array(
		'name' => 'submit',
		'value' => 'Change Password',
		'class' => 'button'
	);
	echo form_submit($attributes);
	?>
	</div>
	<?= form_close() ?>
<? endif; ?>
