<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Heatmaprw extends MY_Model {
	public function __construct() {
		parent::MY_Model();
		$this->collection = 'clicks';

		$this->mangoSelectCollection();
		$this->load->database();
	}

	public function get_clicks($app_id, $loc, $start_date, $end_date){
		$this->mangoSelectCollection();

		$start_date *= 1000.0;
		$end_date *= 1000.0;

		$this->mangoci->constraints(
			array(
				'app_id' => $app_id,
				'loc' => $loc,
				'date' => array(
					'$gte' => $start_date,
					'$lte' => $end_date
				)
			)
		);
		$this->mangoci->find();
		$this->mangoci->limit(1000);
		return $this->mangoci->results();
	}

	public function generate_key(){
		$candidate = NULL;
		while(TRUE){
			$candidate = md5(rand().time().rand());

			$this->db->where('heatmap_keys.key', $candidate);
			if($this->db->count_all_results('heatmap_keys') == 0){
				break;
			}
		}

		$insert = array(
			'key' => $candidate,
			'expires' => time() + 60*60*24 //1 day
		);
		$this->db->insert('heatmap_keys', $insert);

		return $candidate;
	}

	public function validate($key){
		$this->db->where('heatmap_keys.key', $key);
		$this->db->where('heatmap_keys.expires >=', time());

		if($this->db->count_all_results('heatmap_keys') > 0){
			return TRUE;
		}
		return FALSE;
	}

	public function invalidate_key($key){
		$this->db->where('heatmap_keys.key', $key);
		$this->db->delete('heatmap_keys');
	}
}

/* End of file heatmaprw.php */
