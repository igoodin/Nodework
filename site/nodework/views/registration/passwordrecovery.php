<h1>Recover Password</h1>

<div><?= validation_errors() ?></div>

<?= form_open('registration/passwordrecovery') ?>

<div id="db-fields">

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
			<label for="user-db-email">Confirm Email Address</label>
		</div>
		<div class="value">
			<?
			$attributes = array(
				'name' => 'db_confirm_email',
				'value' => set_value('db_confirm_email'),
				'id' => 'user-db-email'
			);
			echo form_input($attributes);
			?>
		</div>
	</div>

	<div class="submit">
		<?
		$attributes = array(
			'name' => 'db_submit',
			'value' => 'Recover Password',
			'class' => 'button'
		);
		echo form_submit($attributes);
		?>
	</div>

</div>
</div>
<?= form_close() ?>

