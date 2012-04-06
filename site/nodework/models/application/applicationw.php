<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applicationw extends Model {
	public function __construct(){
		parent::Model();
		$this->load->database();

		$this->load->library('mangoci');
	}

	public function create($project_data){
		$this->db->insert('applications', $project_data);

		return $this->db->insert_id();
	}

	public function update($app_id, $app){
		$this->db->where('applications.application_id', $app_id);
		$this->db->update('applications', $app);
	}

	public function add_user($app_id, $user_id, $permission){
		$insert = array(
			'application_id' => $app_id,
			'user_id' => $user_id,
			'permission' => $permission
		);
		$this->db->insert('application_ref_user', $insert);
	}

	public function remove_user($app_id, $user_id){
		$this->db->where('application_id', $app_id);
		$this->db->where('user_id', $user_id);
		$this->db->delete('application_ref_user');
	}

	public function delete($app_id){
		$this->db->where('applications.application_id', $app_id);
		$this->db->delete('applications');
	}

	public function deletesettings($app_id){
		$this->mangoci->SelectCollection('app_settings');

		$this->mangoci->constraints(array('app_id' => $app_id));
		$this->mangoci->remove();
	}
	public function createsettings($data){
		$this->mangoci->SelectCollection('app_settings');

		$this->mangoci->insert($data);
	}

	public function updatesettings($app_id,$data){
		$this->mangoci->SelectCollection('app_settings');

		$this->mangoci->constraints(array('app_id' => $app_id));
		$this->mangoci->update($data);
	}
}

/* End of file application/applicationw.php */
