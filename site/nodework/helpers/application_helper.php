<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function build_javascript($app_id, $node_loc){
	ob_start();
	?>
<script type="text/javascript">
	var __app_id = "<?= $app_id ?>";
	var __host = "<?= $node_loc ?>";

	var __script = document.createElement("script");
	__script.type = "text/javascript";
	__script.src = __host+"/js/analytics.js?q="+Math.random();
	document.getElementsByTagName("script")[0].parentNode.appendChild(__script);
</script>
	<?

	return ob_get_clean();
}

/* End of file application_helper.php */
