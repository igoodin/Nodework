<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settingw extends Model {
	public function __construct() {
		parent::Model();

		$this->load->database();
	}

	public function update_settings($settings){
		foreach($settings as $key=>$value){
			$this->db->where('settings.setting_key', $key);
			$this->db->update('settings', array('setting_value' => $value));
		}
	}
}

/* End of file settingw.php */
