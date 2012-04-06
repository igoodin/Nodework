<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settingr extends Model {
	public function __construct() {
		parent::Model();

		$this->load->database();
	}

	public function get_all(){
		$this->db->select();
		$this->db->from('settings');

		$results = $this->db->get()->result_array();

		if(! empty($results)){
			return $results;
		}
		return NULL;
	}

	public function get_setting($setting_key){
		$this->db->select('settings.setting_value');
		$this->db->from('settings');
		$this->db->where('settings.setting_key', $setting_key);

		$record = $this->db->get()->row_array();

		if(! empty($record)){
			return $record['setting_value'];
		}
		return NULL;
	}
}

/* End of file settingr.php */
