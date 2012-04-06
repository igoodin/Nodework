<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userw extends Model {
	public function __construct() {
		parent::Model();

		$this->load->database();
	}

	public function create_local($user){
		//hashlib loaded in controller
		$user['salt'] = $this->make_salt($user);
		$user['password'] = $this->hashlib->enc_password($user['password'], $user['salt']);
		$user['ldap_domain'] = NULL; //just for safe measure

		$this->db->insert('users', $user);

		return $this->db->insert_id();
	}

	public function update_password($user_id, $new_password){
		$update = array(
			'salt' => $this->make_salt(rand().time())
		);

		$update['password'] = $this->hashlib->enc_password($new_password, $update['salt']);

		$this->db->where('users.user_id', $user_id);
		$this->db->update('users', $update);
	}

	public function update($user_id, $user){
		$this->db->where('users.user_id', $user_id);
		$this->db->update('users', $user);
	}

	public function destroy($user_id){
		$this->db->where('users.user_id', $user_id);
		$this->db->delete('users');
	}

	//FOR REGISTRATION
	function create_reg_key($user_id){
		//delete any existing keys for this user
		$this->db->where('user_id', $user_id);
		$this->db->delete('registration_keys');

		$data = array();
		$data['user_id'] = $user_id;
		$data['key'] = md5(time().rand().$data['user_id']);
		$data['expires'] = time() + 60*60*24*2; //now + 2 days

		$this->db->insert('registration_keys', $data);

		return $data['key'];
	}

	function confirm_registration($user_id){
		//mark user as active
		$data = array('is_active' => 1);
		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data);

		//delete their old registration key
		$this->db->where('user_id', $user_id);
		$this->db->delete('registration_keys');
	}

	//FOR PASSWORD RECOVERY
	function create_recovery_key($user_id){
		//delete any existing keys for this user
		$this->db->where('user_id', $user_id);
		$this->db->delete('recovery_keys');

		$data = array();
		$data['user_id'] = $user_id;
		$data['key'] = md5(time().rand().$data['user_id']);
		$data['expires'] = time() + 60*60*24*2; //now + 2 days

		$this->db->insert('recovery_keys', $data);

		return $data['key'];
	}

	function remove_recovery($user_id){
		//delete their old recovery keys
		$this->db->where('user_id', $user_id);
		$this->db->delete('recovery_keys');
	}
/////////////
	private function make_salt($seed){
		return hash('adler32', json_encode($seed).rand());
	}
}

/* End of file userw.php */
