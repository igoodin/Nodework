<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobiles extends MY_Model {
	public function __construct(){
		parent::MY_Model();

		$this->collection = 'mobiles';

		$this->mangoSelectCollection();
	}

	public function get_stats_summary($app_id, $date_start, $date_end){
		$this->mangoSelectCollection();

		$this->mangoci->constraints(
			array(
				'app_id' => $app_id,
				'date_nix' => array(
					'$gte' => $date_start,
					'$lte' => $date_end
				)
			)
		);
		$this->mangoci->find();
		$this->mangoci->sort(array('count' => -1));

		return $this->mangoci->results();
	}

	public function delete_app($app_id){
		$this->mangoSelectCollection();

		$this->mangoci->constraints(array('app_id' => $app_id));
		$this->mangoci->remove();
	}
}

/* End of file mobiles.php */
