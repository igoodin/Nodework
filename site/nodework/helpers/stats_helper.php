<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function format_stat($number=0){
	if($number >= 1000000){
		$number = intval($number / 1000000);
		$result = strval($number);
		$result .= 'M';
	}
	elseif($number >= 10000){
		$number = intval($number/1000);
		$result = strval($number);
		$result .= 'k';
	}
	else{
		$result = $number;
	}

	return $result;
}

/* End of file stats_helper.php */
