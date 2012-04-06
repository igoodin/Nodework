<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applicationr extends Model {
	public function __construct(){
		parent::Model();
		$this->load->database();

		$this->load->library('mangoci');
	}

	public function get($app_id){
		$this->db->select();
		$this->db->from('applications');
		$this->db->where('applications.application_id', $app_id);

		$result = $this->db->get()->row_array();

		if(! empty($result)){
			return $result;
		}
		return NULL;
	}

	public function application_scale($app_id){
		$this->db->select('applications.application_align');
		$this->db->from('applications');
		$this->db->where('applications.application_id', $app_id);

		$record = $this->db->get()->row_array();

		if(! empty($record)){
			if($record['application_align'] == 'scale'){
				return TRUE;
			}
			return FALSE;
		}
		return NULL;
	}

	public function get_all(){
		$this->db->select();
		$this->db->from('applications');

		return $this->db->get()->result_array();
	}

	public function get_user_apps($user_id){
		$this->db->select();
		$this->db->from('applications');
		$this->db->join('application_ref_user', 'application_ref_user.application_id=applications.application_id');
		$this->db->where('application_ref_user.user_id', $user_id);

		return $this->db->get()->result_array();
	}

	public function get_user_app_perms($user_id, $app_id){
		$this->db->select('permission');
		$this->db->from('application_ref_user');
		$this->db->where('application_ref_user.user_id', $user_id);
		$this->db->where('application_ref_user.application_id', $app_id);

		return $this->db->get()->row_array();
	}

	public function get_users($app_id){
		$this->db->select();
		$this->db->from('users');
		$this->db->join('application_ref_user', 'users.user_id=application_ref_user.user_id');
		$this->db->where('application_ref_user.application_id', $app_id);

		return $this->db->get()->result_array();
	}

	public function getsettings($app_id){
		$this->mangoci->SelectCollection('app_settings');

		$this->mangoci->constraints(array('app_id' => $app_id));

		return $this->mangoci->findOne();
	}
}

/* End of file application/applicationr.php */
