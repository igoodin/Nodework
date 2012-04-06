<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Times extends MY_Model {
	public function __construct(){
		parent::MY_Model();

		$this->collection = 'times';

		$this->mangoSelectCollection();
	}

	public function get_stats_summary($app_id, $date_start, $date_end){
		$this->mangoSelectCollection();

		$date_start = intval($date_start);
		$date_end = intval($date_end);

		$constraints = array(
			'app_id' => $app_id,
			'date_nix' => array(
				'$gte' => $date_start,
				'$lte' => $date_end
			)
		);

		$this->mangoci->constraints($constraints);
		$this->mangoci->find();

		return $this->mangoci->results();
	}

	public function delete_app($app_id){
		$this->mangoSelectCollection();

		$this->mangoci->constraints(array('app_id' => $app_id));
		$this->mangoci->remove();
	}
}

/* End of file times.php */
