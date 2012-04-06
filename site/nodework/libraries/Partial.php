<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partial {
	private $_CI;

	public function __construct() {
		$this->_CI = &get_instance();
	}

	public function build($partial_file, $vars=array()){
		return $this->_CI->load->view($partial_file, $vars, TRUE);
	}
}

/* End of file Partial.php */
