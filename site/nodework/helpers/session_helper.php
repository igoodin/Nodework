<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function is_logged_in(){
	$_CI = &get_instance();

	$user = $_CI->session->userdata('user');
	if(! empty($user)){
		return TRUE;
	}
	return FALSE;
}

function user_id(){
	if(! is_logged_in()){
		return FALSE;
	}

	$_CI = &get_instance();
	$user = $_CI->session->userdata('user');
	return $user['user_id'];
}

function username(){
	if(! is_logged_in()){
		return FALSE;
	}

	$_CI = &get_instance();
	$user = $_CI->session->userdata('user');
	return $user['username'];
}

function is_admin(){
	if(! is_logged_in()){
		return FALSE;
	}

	$_CI = &get_instance();
	$user = $_CI->session->userdata('user');
	if($user['user_type'] == 'admin'){
		return TRUE;
	}
	return FALSE;
}

function is_ldap(){
	if(! is_logged_in()){
		return FALSE;
	}

	$_CI = &get_instance();
	$user = $_CI->session->userdata('user');
	if(! empty($user['ldap_domain'])){
		return TRUE;
	}
	return FALSE;
}

/* End of file session_helper.php */
