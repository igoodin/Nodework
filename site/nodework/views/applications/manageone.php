<input type="hidden" id="app-id" value="<?= $app['application_id'] ?>" />

<h1>
	<?= $app['application_name'] ?>
	(<?= $app['application_domain'] ?>)
	- Users
</h1>

<div id="search-user">
	<div class="section-header">Add User</div>
	<div id="search">
		<label for="search-people">Add a User</label>
		<input type="text" id="search-people" />

		<b>that has</b>

		<select id="select-permission-level">
			<option value="r">Read</option>
			<option value="rw">Read + Write</option>
		</select>

		<b>privilages.</b>

		<button id="button-add-user" disabled>Go!</button>
	</div>
</div>

<div class="section-header">Existing Users</div>

<? if(empty($users)): ?>
<div class="no-items">
	This Application Has No Users
</div>
<? endif; ?>

<? foreach($users as $user): ?>
<div class="list-item">
	<div class="links">
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
		<span class="no-bold">
			(<?= $user['username'] ?>)
		</span>
	<? endif; ?>

	<? if(! empty($user['email'])): ?>
	<span class="subname">
		- <?= $user['email'] ?>
	</span>
	<? endif; ?>
	<span class="red"><small>
		<? if($user['permission'] == 'rw'): ?>
			(read + write
		<? elseif($user['permission'] == 'r'): ?>
			(read
		<? endif; ?>
		permissions)
	</small></span>
	</div>
</div>
<? endforeach; ?>

<script type="text/javascript">
jQuery(document).ready(function(){
	var app_id = jQuery("input#app-id").val();
	
	var user_id = false;
	jQuery("#search-people").autocomplete({
		source: base_url + "users/search",
		minLength: 2,
		select: function(event, ui){
			user_id = ui.item.id;
			jQuery("#button-add-user").button("option", "disabled", false);
		}
	});

	jQuery("button#remove-user").click(function(){
		var user_id = jQuery(this).attr("uid");
		if(confirm("Are you sure?")){
			jQuery.ajax({
				type: "POST",
				dataType: "json",
				url: base_url + "applications/" + app_id + "/users/destroy",
				data:{"user_id":user_id},
				success:function(){
					redirect('applications/manage/'+app_id);
				}
			});
		}
	});

	jQuery("#button-add-user").click(function(){
		var permission = jQuery("select#select-permission-level").val();
		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: base_url + 'applications/'+app_id+'/users/create',
			data:{"user_id":user_id, "perm":permission},
			success:function(){
				redirect('applications/manage/'+app_id);
			},
		});
	});
});
</script>
