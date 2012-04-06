<h1>Password restore</h1>

<div><?= validation_errors() ?></div>

<?= form_open('registration/completerecovery/'.$key) ?>

<div id="db-fields">

	<div class="field">
		<div class="label">
			<label for="user-db-password">New Password</label>
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
			<label for="user-db-password">Confirm New Password</label>
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
			'value' => 'Confirm',
			'class' => 'button'
		);
		echo form_submit($attributes);
		?>
	</div>

</div>
</div>
<?= form_close() ?>

