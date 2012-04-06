<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Controller {
	public function __construct() {
		parent::Controller();

		//require the user to be logged in
		if(! is_logged_in()){
			redirect('sessions/create');
		}
	}

	public function create(){
		//start security
		//inherit from constructor
		if(! is_admin()){
			$this->messages->report_failure('Unable to perform action');
			redirect();
		}
		//end security

		if(! empty($_POST)){
			$this->load->library('form_validation');
			$this->load->library('hashlib');
			$this->load->model('users/userw');
			if(! empty($_POST['db_submit'])){
				if($this->form_validation->run('users/create_db')){
					$user = array(
						'username' => $this->input->post('db_username'),
						'firstname' => $this->input->post('db_firstname'),
						'lastname' => $this->input->post('db_lastname'),
						'email' => $this->input->post('db_email', TRUE),
						'password' => $this->input->post('db_password', TRUE),
						'user_type' => $this->input->post('db_permission_group')
					);

					$this->userw->create_local($user);

					$this->messages->report_success("Local User Sucessfully Created");
					redirect('users/show');
				}
			}
			elseif(! empty($_POST['ldap_submit'])){
				if($this->form_validation->run('users/create_ldap')){
					$user = array(
						'username' => $this->input->post('ldap_username'),
						'firstname' => $this->input->post('ldap_firstname'),
						'lastname' => $this->input->post('ldap_lastname'),
						'email' => $this->input->post('ldap_email', TRUE),
						'ldap_domain' => $this->input->post('ldap_domain'),
						'user_type' => $this->input->post('ldap_permission_group')
					);

					$this->userw->create_ldap($user);

					$this->messages->report_success("LDAP User Sucessfully Created!");
					redirect('users/show');
				}
			}
		}

		$this->load->helper('form');

		$this->load->view('header');
		$this->load->view('users/create');
		$this->load->view('footer');
	}

	public function show(){
		//start security
		//inherit from constructor
		if(! is_admin()){
			$this->messages->report_failure('Unable to perform action');
			redirect();
		}
		//end security

		$this->load->model('users/userr');

		$users = $this->userr->get_all();

		$this->load->view('header');
		$data = array('users' => $users);
		$this->load->view('users/show', $data);
		$this->load->view('footer');
	}

	public function update(){
		//start security
		//inherit from constructor
		if(! is_admin()){
			$this->messages->report_failure('Unable to perform action');
			redirect();
		}
		//end security

		$this->load->model('users/userr');

		$user_id = $this->uri->segment(3);

		if(! empty($_POST)){
			$this->load->model('users/userw');
			$this->load->library('form_validation');
			if(! empty($_POST['db_submit'])){
				if($this->form_validation->run('users/update_db')){
					$user = array(
						'firstname' => $this->input->post('db_firstname', TRUE),
						'lastname' => $this->input->post('db_lastname', TRUE),
						'email' => $this->input->post('db_email', TRUE),
						'user_type' => $this->input->post('db_permission_group')
					);

					$this->userw->update($user_id, $user);

					$this->messages->report_success("User Sucessfully Updated!");
					redirect('users/show');
				}
			}
		}

		$user = $this->userr->get($user_id);

		$this->load->helper('form');

		$this->load->view('header');
		$data = array('user' => $user);
		$this->load->view('users/update', $data);
		$this->load->view('footer');
	}

	public function profile(){
		$this->load->model('users/userr');

		if(! empty($_POST)){
			$this->load->library('form_validation');
			if($this->form_validation->run('users/profile')){
				$this->load->model('users/userw');
				$this->load->library('hashlib');

				$password = $this->input->post('cur_password');
				$new_password = $this->input->post('new_password');

				if($this->userr->local_authorize(username(), $password)){
					$this->userw->update_password(user_id(), $new_password);

					$this->messages->report_success("Password Sucessfully Updated!");
					redirect('applications/show');
				}
				else{ //invalid password
					$this->messages->report_failure("Invalid Password");
				}
			}
		}

		$user = $this->userr->get(user_id());

		$this->load->helper('form');

		$this->load->view('header');
		$data = array('user' => $user);
		$this->load->view('users/profile', $data);
		$this->load->view('footer');
	}

	public function search(){
		//start security
		//inherit from constructor
		if(! is_admin()){
			$this->messages->report_failure('Unable to perform action');
			redirect();
		}
		//end security

		$this->load->model('users/userr');

		parse_str($_SERVER['QUERY_STRING'], $getVars);

		$query = $this->input->xss_clean($getVars['term']);

		$results = $this->userr->search($query);

		$ret_results = array();
		foreach($results as $result){
			$person = '';
			if(! empty($result['firstname'])){
				$person .= $result['firstname'];

				if(! empty($result['lastname'])){
					$person .= " {$result['lastname']}";
				}

				$person .= " ({$result['username']})";
				if(! empty($result['email'])){
					$person .= " - {$result['email']}";
				}
			}
			else{
				$person .= $result['username'];
				if(! empty($result['email'])){
					$person .= " {$result['email']}";
				}
			}
			$ret_results[] = array(
				'id' => $result['user_id'],
				'value' => $person
			);
		}

		header('Content-Type: application/json');
		echo json_encode($ret_results);
		exit;
	}

	public function destroy(){
		//start security
		//inherit from constructor
		if(! is_admin()){
			$this->messages->report_failure('Unable to perform action');
			redirect();
		}
		//end security

		if(! isset($_POST['user_id'])){
			exit;
		}
		$this->load->model('users/userw');

		$user_id = $this->input->post('user_id', TRUE);

		$this->userw->destroy($user_id);

		$this->messages->report_success("User Deleted");

		header('Content-Type: application/json');
		echo json_encode(array('stat' => TRUE));
		exit;
	}

//////////////////////
	public function _unique_username($username){
		$this->load->model('users/userr');

		$this->form_validation->set_message('_unique_username', 'Username Already In Use');

		if($this->userr->check_unique_username($username)){
			return TRUE;
		}
		return FALSE;
	}
}

/* End of file Users.php */
