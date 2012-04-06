<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//@todo check the leading '0' on the %j - does it exist in mongo?
function date_string($time = FALSE){
	if($time === FALSE){$time = time();}

	return strftime("%Y%j", $time);
}

function to_date_nix($time = FALSE){
	if($time === FALSE){$time = time();}
	$date = getdate($time);
	return mktime(0, 0, 0, intval($date['mon']), intval($date['mday']), intval($date['year']));
}

function utc_to_local($utc_time, $ci_timezone, $daylight_savings=TRUE){
	return gmt_to_local($utc_time, $ci_timezone, $daylight_savings);
}

/* End of file node_helper.php */
