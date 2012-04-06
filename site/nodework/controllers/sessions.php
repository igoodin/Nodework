<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sessions extends Controller {
	public function __construct() {
		parent::Controller();
	}

	public function create(){
		if(! empty($_POST)){
			$this->load->library('form_validation');

			if($this->form_validation->run('sessions/create')){
				$this->load->model('users/userr');
				$this->load->library('hashlib');

				$email = $this->input->post('email');
				$password = $this->input->post('password');

				$user = $this->userr->get_by_email($email);

				$login = FALSE;
				if($user !== FALSE){
					if($this->userr->local_authorize($email, $password)){
						$login = $this->login($user);
					}
				}

				if(! $login){
					$this->messages->report_failure('Incorrect email or Password');
				}

				redirect();
			}
		}

		$this->load->helper('form');
		$this->load->view('header');
		$this->load->view('sessions/create');
		$this->load->view('footer');
	}

	public function destroy(){
		if(! is_logged_in()){
			redirect();
		}
		$logout_key = $this->uri->segment(3);

		$sess_logout_key = $this->session->userdata('logout_key');

		if($sess_logout_key == $logout_key){
			$this->session->sess_destroy();
		}
		redirect();
	}

/////////////////////////
	private function login($user){
		$this->session->set_userdata('user', $user);
		$this->session->set_userdata('logout_key', md5(rand().time()));

		return TRUE;
	}
}

/* End of file Sessions.php */
