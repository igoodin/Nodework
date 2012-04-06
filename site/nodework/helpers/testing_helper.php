<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function printr($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';
}

function vardump($var){
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

/* End of file testing_helper.php */