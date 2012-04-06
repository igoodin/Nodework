<h1>Registration</h1>

<div><?= validation_errors() ?></div>

<?= form_open('registration/register') ?>

<div id="db-fields">


	<div class="field">
		<div class="label">
			<label for="user-db-firstname">Firstname</label>
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
			<label for="user-db-lastname">Lastname</label>
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
			<label for="user-db-email">Email Address</label>
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
			<label for="user-db-password">Password</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'db_password',
				'id' => 'user-db-password'
			);
			echo form_password($attributes);
			?>
		</div>
	</div>

	<div class="field">
		<div class="label">
			<label for="user-db-password">Confirm Password</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'db_confirm_password',
				'id' => 'user-db-password'
			);
			echo form_password($attributes);
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
</div>
<?= form_close() ?>

