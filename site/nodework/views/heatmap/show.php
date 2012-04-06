<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<?load_jquery(); load_jqueryui(); set_css('css/heatmap.css');?>
	<?get_css(); get_dump(); get_js(); get_other()?>
	<title><? global $title; echo (empty($title) ? 'Nodework' : $title);?></title>
	<script type="text/javascript">
	jQuery(window.nameheatmap).ready(function(){
		var width = window.innerWidth;
		var frame = document.getElementById("heatmap");
		frame.onload = function(){
			alert(frame.base_url);
			alert(window.nameheatmap.base_url);
		}
	});
	</script>
<head>
<body>
<div>
	<div>
		<?= anchor("applications/show/$app_id", 'Go Back')?>
	</div>

	<iframe  name="nameheatmap" id="heatmap" src="<?= $page ?>" width="100%" height="100%">
	</iframe>
</div>
</body>
</html>
