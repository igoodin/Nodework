<h1>Administration</h1>

<div class="list-item">
	<div class="links">
		<?= anchor(
			'users/create',
			'Go!',
			array('class' => 'button')
			)
		?>
	</div>

	<div class="name">
		Create New User
	</div>
</div>

<div class="list-item">
	<div class="links">
		<?= anchor(
			'users/show',
			'Go!',
			array('class' => 'button')
			)
		?>
	</div>

	<div class="name">
		Manage Existing Users
	</div>
</div>

<div class="list-item">
	<div class="links">
		<?= anchor(
			'applications/create',
			'Go!',
			array('class' => 'button')
			)
		?>
	</div>

	<div class="name">
		Track New Application
	</div>
</div>

<div class="list-item">
	<div class="links">
		<?= anchor(
			'applications/manage',
			'Go!',
			array('class' => 'button')
			)
		?>
	</div>

	<div class="name">
		Manage Existing Applications
	</div>
</div>
