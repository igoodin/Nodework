<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appinterface extends MY_Model {
	public function __construct(){
		parent::MY_Model();

		$this->collection = 'apps';

		$this->mangoSelectCollection();
	}

//////////// SNAPSHOT (single day)
	public function get_traffic_snapshot($app_id, $date_nix){
		$this->mangoSelectCollection();

		$this->mangoci->constraints(array(
			'app_id' => $app_id,
			'date_nix' => $date_nix
		));
		$this->mangoci->fields(array('uniques', 'requests', 'visitors'));
		$this->mangoci->find();

		$results = $this->mangoci->results();
		if(empty($results)){
			return FALSE;
		}

		return array(
			'requests' => empty($results[0]['requests']) ? 0 : $results[0]['requests'],
			'visitors' => empty($results[0]['visitors']) ? 0 : $results[0]['visitors'],
			'uniques' => empty($results[0]['uniques']) ? 0 : $results[0]['uniques']
		);
	}

/////////// SUMMARY (date range)
	public function get_traffic_summary($app_id, $date_nix_start, $date_nix_end){
		$this->mangoSelectCollection();

		$this->mangoci->constraints(array(
			'app_id' => $app_id,
			'date_nix' => array(
				'$gte' => $date_nix_start,
				'$lte' => $date_nix_end
			)
		));
		$this->mangoci->fields(array('uniques', 'requests', 'visitors'));
		$this->mangoci->find();

		$results = $this->mangoci->results();

		$ret_results = array(
			'requests' => 0,
			'visitors' => 0,
			'uniques' => 0
		);
		foreach($results as $result){
			if(!empty($result['requests'])){
				$ret_results['requests'] += $result['requests'];
			}
			if(!empty($result['visitors'])){
				$ret_results['visitors'] += $result['visitors'];
			}
			if(!empty($result['uniques'])){
				$ret_results['uniques'] += $result['uniques'];
			}
		}

		return $ret_results;
	}
}

/* End of file appinterface.php */