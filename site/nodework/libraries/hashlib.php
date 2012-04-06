<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hashlib {
	public function enc_password($password, $hash){
		return hash('md5', $password.$hash);
	}
}

/* End of file hashlib.php */
