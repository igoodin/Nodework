<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userr extends Model {
	public function __construct() {
		parent::Model();

		$this->load->database();
	}

	public function get_all(){
		$this->db->select();
		$this->db->from('users');

		return $this->db->get()->result_array();
	}

	public function get($user_id){
		$this->db->select();
		$this->db->from('users');
		$this->db->where('users.user_id', $user_id);

		$result = $this->db->get()->row_array();

		if(! empty($result)){
			return $result;
		}
		return NULL;
	}

	public function get_by_email($email){
		$this->db->select();
		$this->db->from('users');
		$this->db->where('users.email', $email);

		$record = $this->db->get()->row_array();
		if(! empty($record)){
			return $record;
		}
		return FALSE;
	}

	public function check_unique_email($email){
		$this->db->where('users.email', $email);

		if($this->db->count_all_results('users') > 0){
			return FALSE;
		}
		return TRUE;
	}

	public function local_authorize($email, $password){
		$user = $this->get_by_email($email);

		$enc_pass = $user['password'];
		$salt = $user['salt'];

		//hashlib loaded in controller
		if($enc_pass == $this->hashlib->enc_password($password, $salt)&& $user['is_active']==1){
			return TRUE;
		}
		return FALSE;
	}

	public function search($term){
		$this->db->select('user_id, username, firstname, lastname, email');
		$this->db->from('users');
		$this->db->like('users.username', $term);
		$this->db->or_like('users.firstname', $term);
		$this->db->or_like('users.lastname', $term);
		$this->db->or_like('users.email', $term);

		return $this->db->get()->result_array();
	}

	//FOR REGISTRATION
	function get_registration($identifier){
		$this->db->select();
		$this->db->from('registration_keys');
		$this->db->join('users',
			'registration_keys.user_id=users.user_id');

		$this->db->where('registration_keys.key', $identifier);

		$result = $this->db->get();
		$result = $result->row_array();

		if(! empty($result)){
			return $result;
		}
		//else
		return FALSE;
	}

	function is_valid_registration($registration_key){
		$registration = $this->get_registration($registration_key);

		if(!$registration){
			return FALSE;
		}
		//else
		if($registration['expires'] > time()){
			return TRUE;
		}
		//else
		//registration key expired,  garbage collector should delete upon execution
		return FALSE;
	}

	//FOR PASSWORD RECOVERY
	function get_recovery($identifier){
		$this->db->select();
		$this->db->from('recovery_keys');
		$this->db->join('users',
			'recovery_keys.user_id=users.user_id');

		$this->db->where('recovery_keys.key', $identifier);

		$result = $this->db->get();
		$result = $result->row_array();

		if(! empty($result)){
			return $result;
		}
		//else
		return FALSE;
	}

	function is_valid_recovery($recovery_key){
		$recovery = $this->get_recovery($recovery_key);

		if(!$recovery){
			return FALSE;
		}
		//else
		if($recovery['expires'] > time()){
			return TRUE;
		}
		//else
		//registration key expired,  garbage collector should delete upon execution
		return FALSE;
	}

	function is_real_recovery($recovery_key){
		$recovery = $this->get_recovery($recovery_key);

		if(!$recovery){
			return FALSE;
		}
		return TRUE;
	}

	function is_expired_recovery($recovery_key){
		$recovery = $this->get_recovery($recovery_key);

		if($recovery['expires'] < time()){
			return FALSE;
		}
		return TRUE;
	}




}

/* End of file userr.php */
