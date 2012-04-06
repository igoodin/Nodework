<h1>Update Application</h1>

<input type="hidden" value="<?= $app['application_id'] ?>" id="app-id" />

<?= form_open("applications/update/{$app['application_id']}") ?>

<div>
	<?= validation_errors() ?>
</div>

<div>
	<div>
		<label for="application_name">Name</label>
	</div>

	<div>
		<?
		$attributes = array(
			'name' => 'name',
			'id' => 'application_name',
			'value' => $app['application_name']
		);
		echo form_input($attributes);
		?>
	</div>
</div>

<div>
	<div>
		<label for="application_domain">Domain</label>
	</div>

	<div>
		<?
		$attributes = array(
			'name' => 'domain',
			'id' => 'application_domain',
			'value' => $app['application_domain']
		);
		echo form_input($attributes);
		?>
	</div>
</div>

<div>
	<div>
		<label for="application_params">Exclude Url parameters (comma seperated)</label>
	</div>

	<div>
		<?
		$attributes = array(
			'name' => 'params',
			'id' => 'application_params',
			'value' => $app['application_params']
		);
		echo form_input($attributes);
		?>
	</div>
</div>
<div>
	<div>
		<label for="application_align">Page Alignment</label>
	</div>

	<div>
		<input type="radio" name="align" value="noscale" <?if($app['application_align']=="noscale"):?>checked="checked"<?endif;?>/>
		<label for="application_align">Left</label>
		<input type="radio" name="align" value="scale" <?if($app['application_align']=="scale"):?>checked="checked"<?endif;?>/>
		<label for="application_align">Center</label>
	</div>
</div>
<div>
	<?
	$attributes = array(
		'name' => 'submit',
		'value' => 'Update!',
		'class' => 'button'
	);
	echo form_submit($attributes);
	?>
	<button type="button" id="button-delete-application" class="ui-state-error">Delete Application</button>
</div>

<?= form_close() ?>

<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("button#button-delete-application").click(function(){
		var app_id = jQuery("input#app-id").val();
		if(confirm("Are you sure?")){
			jQuery.ajax({
				type:"POST",
				dataType: "json",
				url: base_url+"applications/destroy/"+app_id,
				success:function(){
					redirect("applications/manage");
				}
			});
		}
	});
});
</script>
