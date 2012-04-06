<h1>Create New User</h1>

<div>
	<?= validation_errors() ?>
</div>

<?= form_open('users/create') ?>

<div class="field">
	<label for="user-type-dropdown">User Type</label>
	<?
	$options = array(
		'db' => 'Local User',
		'ldap' => 'LDAP User'
	);
	echo form_dropdown('user_type', $options, set_value('user_type'), 'id="select-user-type"');
	?>
</div>

<div id="db-fields">
	<div class="field">
		<div class="label">
			<label for="user-db-username">Username</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'db_username',
				'value' => set_value('db_username'),
				'id' => 'user-db-username'
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
				'value' => set_value('db_firstname'),
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
				'value' => set_value('db_lastname'),
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
				'value' => set_value('db_email'),
				'id' => 'user-db-email'
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-db-password">Password (user can change)</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'db_password',
				'value' => set_value('db_password'),
				'id' => 'user-db-password'
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
			echo form_dropdown('db_permission_group', $options, set_value('db_permission_group'));
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

<div id="ldap-fields" class="hidden">
	<div class="field">
		<div class="label">
			<label for="user-ldap-username">Username</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'ldap_username',
				'value' => set_value('ldap_username'),
				'id' => 'user-ldap-username'
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
				'value' => set_value('ldap_domain'),
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
				'value' => set_value('ldap_firstname'),
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
				'value' => set_value('ldap_lastname'),
				'id' => 'user-ldap-lastname'
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-ldap-email">Email Address (optional)</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'ldap_email',
				'value' => set_value('ldap_email'),
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
			echo form_dropdown('ldap_permission_group', $options, set_value('ldap_permission_group'));
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

<?= form_close() ?>

<script type="text/javascript">
function enable_db_fields(){
	jQuery("div#ldap-fields").hide();
	jQuery("div#db-fields").show();
}
function enable_ldap_fields(){
	jQuery("div#db-fields").hide();
	jQuery("div#ldap-fields").show();
}
jQuery(document).ready(function(){
	var select = jQuery("select#select-user-type");
	if(select.val() == "db"){
		enable_db_fields();
	}
	else{
		enable_ldap_fields();
	}
	select.change(function(){
		var type = jQuery("select#select-user-type").val();
		if(type == "db"){
			enable_db_fields();
		}
		else{
			enable_ldap_fields();
		}
	});
});
</script>
