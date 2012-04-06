<h1>Manage Users</h1>

<? foreach($users as $user): ?>
<div class="list-item">
	<div class="links">
		<?= anchor(
			"users/update/{$user['user_id']}",
			'Update',
			array('class' => 'button')
			)
		?>
		<button uid="<?= $user['user_id'] ?>" class="ui-state-error" id="remove-user">Remove</button>
	</div>
	<? $printed_username = FALSE ?>
	<div class="name">
	<?
		if(! empty($user['firstname'])){
			echo $user['firstname'];
			if(! empty($user['lastname'])){
				echo " {$user['lastname']}";
			}
		}
		else{
			echo $user['username'];
			$printed_username = TRUE;
		}
	?>
	<? if(! $printed_username): ?>
		<span class="no-bold italic">
			(<?= $user['username'] ?>)
		</span>
	<? endif; ?>

	<? if(! empty($user['email'])): ?>
	<span class="subname italic">
		- <?= $user['email'] ?>
	</span>
	<? endif; ?>
	</div>
</div>
<? endforeach; ?>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("button#remove-user").click(function(){
		var user_id = jQuery(this).attr("uid");
		if(confirm("Are you sure?")){
			jQuery.ajax({
				type:"POST",
				dataType:"json",
				url:base_url+"users/destroy",
				data:{"user_id":user_id},
				success:function(){
					redirect("users/show");
				}
			});
		}
	});
});
</script>
