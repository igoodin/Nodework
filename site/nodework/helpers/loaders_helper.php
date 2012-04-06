<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* This helper provides methods to easily include javascript/css files
* and is designed to append the correct HTML to the <head> tag.
*/

/**
* Places a CSS file in the header
*
* @param string The CSS file
* @param bool If the file should be includes as a print stylesheet only
*/
function set_css($css, $print_only=false){
	global $css_array;
	if(isset($css_array)){
		$css_array[] = array(
					'file' => $css,
					'print' => $print_only
					);
	}
	else{
		$css_array = array(
			array(
				'file' => $css,
				'print' => $print_only
				));
	}
}

/**
* Places a JS file in the beginning of the header stick
*
* @param string The JS file
*/
function set_global($js){
	global $js_array;
	if(isset($js_array)){
		if(is_array($js_array)){
		    array_unshift($js_array, $js);
		}
		else{
			$js_array = array($js, $js_array);
		}
	}
	else{
		$js_array = array($js);
	}
}

/**
* Sets a JS file in the header
*
* @param string The JS file
*/
function set_js($js){
	global $js_array;
	if(isset($js_array)){
		if(is_array($js_array)){
			$js_array[] = $js;
		}
		else{
			$js_array = array($js_array, $js);
		}
	}
	else{
		$js_array = array($js);
	}
}

/**
* Calls the appropriate methods to load jQuery (from Google's CDN)
*
* @param string The desired version of jQuery
*/
function load_jquery($v='1.4.2'){
	global $dump_array;
	$str = '<script type="text/javascript" src="http://www.google.com/jsapi"></script>';
	$str2 = '<script>google.load("jquery","'.$v.'");</script>';
	if(isset($dump_array)){
		if(is_array($dump_array)){
			//this order is important
			array_unshift($dump_array, $str2);
			array_unshift($dump_array, $str);
		}
		else{
			$dump_array = array($dump_array, $str, $str2);
		}
	}
	else{
		$dump_array = array($str, $str2);
	}
}

/**
* Calls the appropriate methods to load jQueryUI (from Google's CDN)
*
* @param string The desired version of jQueryUI
*/
function load_jqueryui($v = '1.8.2'){
	global $dump_array;

	$str = '<script>google.load("jqueryui", "'.$v.'");</script>';
	if(isset($dump_array)){
		if(is_array($dump_array)){
			$dump_array[] = $str;
		}
		else{
			$dump_array = array($dump_array, $str);
		}
	}
	else{
		$dump_array = array($str);
	}

	set_css('css/juicss.css');
}

/**
* Allows the user to place a custom script into the header
*
* @param string The script to include
*/
function load_other($script){
	global $other_array;
	if(isset($other_array)){
		if(is_array($other_array)){
			$other_array[] = $script;
		}
		else{
			$other_array = array($other_array, $script);
		}
	}
	else{
		$other_array = array($script);
	}
}

/**
* Echos the contents of the dump array
*/
function get_dump(){
	global $dump_array;

	if(!is_array($dump_array) || !isset($dump_array)){
		return FALSE;
	}

	foreach($dump_array as $c){
		echo $c."\n";
	}

	$dump_array = array();
}

/**
* Echos the contents of the other array
*/
function get_other(){
	global $other_array;

	if(!is_array($other_array) || !isset($other_array)){
		return FALSE;
	}

	foreach($other_array as $c){
		$CI = &get_instance();
		$static_dir = $CI->config->item('static_dir');
		$base_url = $CI->config->item('base_url');
		$c = $base_url.$static_dir.$c;
		echo '<script type="text/javascript" src="'.$c.'"></script>'."\n";
	}

	$other_array = array();
}

/**
* Echos the contents of the array containing the CSS files
*/
function get_css(){
	global $css_array;

	if(!is_array($css_array) || !isset($css_array)){
		return FALSE;
	}

	foreach($css_array as $c){
		$CI = &get_instance();
		$static_dir = $CI->config->item('static_dir');
		$base_url = $CI->config->item('base_url');
		$print = $c['print'];
		$c = $base_url.$static_dir.$c['file'];
		if($print){
			echo '<link rel="StyleSheet" href="'.$c.'" media="print"/>'."\n";
		}
		else{
			echo '<link rel="StyleSheet" href="'.$c.'" media="all"/>'."\n";
		}
	}

	$css_array = array();
}

/**
* Echos the contents of the array containing the JS files
*/
function get_js(){
	global $js_array;

	if(!is_array($js_array) || !isset($js_array)){
		return FALSE;
	}

	foreach($js_array as $j){
		$CI = &get_instance();
		$static_dir = $CI->config->item('static_dir');
		$base_url = $CI->config->item('base_url');
		$j = $base_url.$static_dir.$j;
		echo '<script  type="text/javascript" src="'.$j.'"></script>'."\n";
	}

	$js_array = array();
}

/* End of loaders_helper.php */

