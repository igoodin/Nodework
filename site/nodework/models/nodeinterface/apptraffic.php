<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apptraffic extends MY_Model {
	public function __construct(){
		parent::MY_Model();

		$this->collection = 'app_traffic';

		$this->mangoSelectCollection();
	}

	public function get_summary_snapshot($apps, $mongo_date){
		$this->mangoSelectCollection();

		$constraints = array(
			'date' => $mongo_date,
			'app_id' => array(
				'$in' => $apps
			)
		);

		$this->mangoci->constraints($constraints);
		$this->mangoci->find();
		$results = $this->mangoci->results();

		return $results;
	}

	public function get_stats_summary($app_id, $date_start=FALSE, $date_end=FALSE){
		$this->mangoSelectCollection();

		$limit = 30;
		$constraints = array(
			'app_id' => $app_id,
		);
		if($date_start !== FALSE && $date_end !== FALSE){
			$limit = FALSE;
			$constraints['date_nix'] = array(
				'$gte' => $date_start,
				'$lte' => $date_end
			);
		}

		$this->mangoci->constraints($constraints);
		$this->mangoci->find();
		$this->mangoci->sort(array('date_nix' => -1));
		if($limit !== FALSE){
			$this->mangoci->limit($limit);
		}
		$results = $this->mangoci->results();

		return $results;
	}

	public function delete_app($app_id){
		$this->mangoSelectCollection();

		$this->mangoci->constraints(array('app_id' => $app_id));
		$this->mangoci->remove();
	}
}

/* End of file apptraffic.php */
