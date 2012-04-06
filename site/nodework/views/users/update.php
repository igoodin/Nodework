<div>
	<?= validation_errors() ?>
</div>

<?= form_open("users/update/{$user['user_id']}") ?>

<? if(empty($user['ldap_domain'])): ?>

<div id="db-fields">
	<div class="field">
		<div class="label">
			<label for="user-db-username">Username</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'db_username',
				'value' => $user['username'],
				'id' => 'user-db-username',
				'disabled' => TRUE
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-db-firstname">Firstname (optional)</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'db_firstname',
				'value' => $user['firstname'],
				'id' => 'user-db-firstname'
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-db-lastname">Lastname (optional)</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'db_lastname',
				'value' => $user['lastname'],
				'id' => 'user-db-lastname'
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-db-email">Email Address (optional)</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'db_email',
				'value' => $user['email'],
				'id' => 'user-db-email'
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-db-permission-group">User Level</label>
		</div>
		<div class="value">
			<?
			$options = array(
				'user' => 'Normal User',
				'admin' => 'Administrator'
			);
			echo form_dropdown('db_permission_group', $options, $user['user_type']);
			?>
		</div>
	</div>

	<div class="submit">
		<?
		$attributes = array(
			'name' => 'db_submit',
			'value' => 'Create User!',
			'class' => 'button'
		);
		echo form_submit($attributes);
		?>
	</div>

</div>

<? else: ?>

<div id="ldap-fields">
	<div class="field">
		<div class="label">
			<label for="user-ldap-username">Username</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'ldap_username',
				'value' => $user['username'],
				'id' => 'user-ldap-username',
				'disabled' => TRUE
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-ldap-domain">LDAP Domain</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'ldap_domain',
				'value' => $user['ldap_domain'],
				'id' => 'user-ldap-domain'
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-ldap-firstname">Firstname (optional)</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'ldap_firstname',
				'value' => $user['firstname'],
				'id' => 'user-ldap-firstname'
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-ldap-lastname">Lastname (optional)</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' =>'ldap_lastname',
				'value' => $user['lastname'],
				'id' => 'user-ldap-lastname'
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-ldap-email">Email (optional)</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'ldap_email',
				'value' => $user['email'],
				'id' => 'user-ldap-email'
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-ldap-permission-group">User Level</label>
		</div>
		<div class="value">
			<?
			$options = array(
				'user' => 'Normal User',
				'admin' => 'Administrator'
			);
			echo form_dropdown('ldap_permission_group', $options, $user['user_type']);
			?>
		</div>
	</div>

	<div class="submit">
		<?
		$attributes = array(
			'name' => 'ldap_submit',
			'value' => 'Create User!',
			'class' => 'button'
		);
		echo form_submit($attributes);
		?>
	</div>
</div>

<? endif; ?>

<?= form_close() ?>

