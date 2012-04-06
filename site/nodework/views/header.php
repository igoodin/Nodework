<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<? load_jquery(); load_jqueryui(); ?>
	<? set_js('js/jquery.tools.min.js'); set_js('js/globals.js'); set_css('css/screen.css'); ?>
	<? get_css(); get_dump(); get_js(); get_other(); ?>

	<!--[if IE]>
	<? set_js('js/excanvas.js'); get_js(); ?>
	<? set_css('css/ie.css'); get_css(); ?>
	<![endif]-->

	<title><? global $title; echo (empty($title) ? 'Nodework' : $title);?></title>
	<script type="text/javascript">
		var base_url = "<?= base_url() ?>index.php/";

		function redirect(url){
			window.location = base_url + url;
		}

		jQuery(document).ready(function(){
			jQuery("button, a.button, input.button").button();
		});
	</script>
</head>
<body>
	<a name="#top"></a>

	<div id="stretch-header">
		<div id="sh-content">
			<div id="sh-user-status">
				<? if(is_logged_in()): ?>
					<i><?= username() ?></i>
					&nbsp;

					<? if(! is_ldap()): ?>
						<?= anchor('users/profile', 'Change Password') ?>
					<? endif; ?>

					<? $logout_key = $this->session->userdata('logout_key') ?>
					<?= anchor(
						'sessions/destroy/'.$logout_key, 
						'Logout',
						array('class' => 'bold', 'id' => 'logout')
					) ?>
				<? else: ?>
					<span class="italic"><?= anchor('sessions/create', 'Login') ?></span>
				<? endif; ?>
			</div>

			<div id="sh-logo">
				<img src="/nodework/nodework/views/static/images/nodeworklogo.png" alt="Nodework logo" />
			</div>

		</div>
	</div>

	<div id="container" class="ui-corner-all">
		<div id="content" class="ui-widget ui-corner-all">

			<div id="tabs" class="tabs">
			<? if(is_logged_in()): ?>
				<?= anchor('applications/show', 'My Applications') ?>
				<? if(is_admin()): ?>
					<?= anchor('applications/adminpanel', 'Admin') ?>
				<? endif; ?>
			<? endif; ?>
			</div>

			<div id="notifications">
				<? $flash_errors = $this->messages->get_failure_messages() ?>
				<? $success_messages = $this->messages->get_success_messages() ?>

				<? if(! empty($flash_errors)): ?>
					<div id="flash-errors">
						<ul>
						<? foreach($flash_errors as $error): ?>
							<li><?= $error ?></li>
						<? endforeach; ?>
						</ul>
					</div>
				<? endif; ?>

				<? if(! empty($success_messages)): ?>
					<div id="success-messages">
						<ul>
						<? foreach($success_messages as $message): ?>
							<li><?= $message ?></li>
						<? endforeach; ?>
						</ul>
					</div>
				<? endif; ?>
			</div>
