<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clicks extends MY_Model {
	public function __construct(){
		parent::MY_Model();

		$this->collection = 'clicks';

		$this->mangoSelectCollection();
	}

	public function delete_app($app_id){
		$this->mangoSelectCollection();

		$this->mangoci->constraints(array('app_id' => $app_id));
		$this->mangoci->remove();
	}
}

/* End of file clicks.php */
