<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ldap {
	public function __construct(){}

	public function ldap_authorize($username, $password, $ldap_domain){
		$ldap_conn = @ldap_connect($ldap_domain);
		if($ldap_conn){
			$bind = @ldap_bind($ldap_conn, "$username@$ldap_domain", $password);
			if($bind){
				return TRUE;
			}
		}
		else{
			$this->messages->report_failure("Authorization failed");
		}

		return FALSE;
	}
}

/* End of file ldap.php */
