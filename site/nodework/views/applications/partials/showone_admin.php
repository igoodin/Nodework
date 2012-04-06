<? $panel = $app_perms == 'rw' || is_admin() ? TRUE : FALSE; ?>

<? if($panel): ?>
<div id="admin-panel">
	<div>
		<div class="notification red-error tall-box">
			<div class="right">
				<button id="button-reset-data" class="ui-state-error">Reset Data</button>
			</div>

			<div class="name bold">Admin</div>
		</div>
	</div>

	<? if(is_admin()): ?>
	<div class="notification blue-notify collapsible-section tall-box">
		<div class="right">
			<button id="js-expand-show" class="button-javascript-expand">Show</button>
			<button id="js-expand-hide" class="button-javascript-expand hidden">Hide</button>
		</div>

		<div class="name bold">
			Click to Expand Javascript Tracking Code
		</div>

		<div class="sh-content">
			<textarea class="js" readonly><?= $js ?></textarea>
		</div>
	</div>

	<? endif; ?>
</div>
<? endif; ?>
