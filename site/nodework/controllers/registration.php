<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Registration extends Controller {
	public function __construct() {
		parent::Controller();

	}

	public function index(){
		redirect("registration/register");
	}

	public function register(){
		//User registration

		if(! empty($_POST)){

			$this->load->library('form_validation');
			$this->load->library('hashlib');
			$this->load->model('users/userw');

			if(! empty($_POST['db_submit'])){
				if($this->form_validation->run('users/create_db')){
					$user = array(
						'firstname' => $this->input->post('db_firstname'),
						'lastname' => $this->input->post('db_lastname'),
						'email' => $this->input->post('db_email', TRUE),
						'password' => $this->input->post('db_password', TRUE),
					);

					$user['is_active']=0;
					$user['user_type']='null';

					//create a user
					$user_id = $this->userw->create_local($user);

					//create a registration key to send to the user for verification
					$reg_key = $this->userw->create_reg_key($user_id);

					//send the verification email
					$this->_send_reg_email($reg_key);

					redirect("registration/emailsent");
				}
			}
		}

		//display the registration form
		$this->load->helper('form');

		$this->load->view('header');
		$this->load->view('registration/register');
		$this->load->view('footer');
	}


	function emailsent(){
		//@todo add info to this page
		$this->load->view('header');
		$this->load->view('registration/emailsent');
		$this->load->view('footer');

	}

	function completeregistration(){
		//Confirm the registration email and activate the user
		$registration_key = $this->uri->segment(3);

		$this->load->model('users/userr');
		$this->load->model('users/userw');

		if($this->userr->is_valid_registration($registration_key)){
			//get the user and registration info and confirm it
			$registration = $this->userr->get_registration($registration_key);
			$this->userw->confirm_registration($registration['user_id']);

			//redirect the user to the login page
			$this->messages->report_success("Email Address Successfully Confirmed");
			redirect("sessions/create");
		}

		redirect();
	}





	function passwordrecovery(){

		if(! empty($_POST)){
			$this->load->library('form_validation');
			$this->load->library('hashlib');
			$this->load->model('users/userw');

			if(! empty($_POST['db_submit'])){
				if($this->form_validation->run('registration/passwordrecovery_db')){
					//if email passes validation send out registration confirmation
					$info = array('email' => $this->input->post('db_email', TRUE),);

					$user = $this->userr->get_by_email($info['email']);
					$user_id = $user['user_id'];

					//create a registration key to send to the user for verification
					$recovery_key = $this->userw->create_recovery_key($user_id);

					//send the verification email
					$this->_send_recovery_email($recovery_key);

					//@todo redirect to a better page
					redirect("registration/emailsent");
				}
			}
		}

		//display the recovery form
		$this->load->helper('form');

		$this->load->view('header');
		$this->load->view('registration/passwordrecovery');
		$this->load->view('footer');
	}

	function completerecovery(){
		$recovery_key = $this->uri->segment(3);

		$this->load->library('form_validation');
		$this->load->library('hashlib');
		$this->load->model('users/userr');
		$this->load->model('users/userw');

		if($this->userr->is_valid_recovery($recovery_key)){

			if(! empty($_POST['db_submit'])){
				if($this->form_validation->run('registration/completerecovery_db')){
					$data = array(
						'password' => $this->input->post('db_password', TRUE),
					);

					//set new password
					$recovery = $this->userr->get_recovery($recovery_key);

					$user_id = $recovery['user_id'];
					$this->userw->update_password($user_id, $data['password']);

					//delete recover keys
					$this->userw->remove_recovery($user_id);

					redirect("registration/recoverysuccessful");
				}
			}

			$recovery = $this->userr->get_recovery($recovery_key);
			$user = $this->userr->get($recovery['user_id']);

			//display the recovery form
			$this->load->helper('form');

			$this->load->view('header');
			$data = array('key'=>$recovery_key);
			$this->load->view('registration/passwordrestore',$data);
			$this->load->view('footer');

		}else{

			if($this->userr->is_real_recovery($recovery_key)){
				//if the recovery key is expired send out a new email and redirect them
				$recovery = $this->userr->get_recovery($recovery_key);
				$user_id = $recovery['user_id'];

				//create a registration key to send to the user for verification
				$recovery_key = $this->userw->create_recovery_key($user_id);

				//send the verification email
				$this->_send_recovery_email($recovery_key);

				$this->load->view('header');
				$this->load->view('registration/recoveryexpired');
				$this->load->view('footer');
			}else{
				redirect();
			}
		}
	}

	function recoverysuccessful(){

		$this->load->view('header');
		$this->load->view('registration/recoverysucessful');
		$this->load->view('footer');
	}

////////////
	function _send_reg_email($registration_id){
		$this->load->library('postmark');
		$this->load->model('users/userr');


		$registration = $this->userr->get_registration($registration_id);


		$this->postmark->to($registration['email'], $registration['firstname']." ".$registration['lastname']);


		$this->postmark->subject('Nodework Registration Confirmation');

		//@todo make this a real email message
		$message = "Dear Person Paying us $,\n\n";
		$message .= "Your registration is almost complete!  Just follow the link below to finish up this last step!\n\n";
		$message .= site_url('registration/completeregistration/'.$registration['key'])."\n\n";
		$message .= "Thank you!\n\n\n\n";
		$message .= "If you did not register for the Illinois State University Passages Portal then please disregard this email and we apologize for the inconvenience.\n";

		$this->postmark->message_plain($message);

		// send the email
		$this->postmark->send();

	}

	function _send_recovery_email($recovery_id){
		$this->load->library('postmark');
		$this->load->model('users/userr');


		$passwordrecovery = $this->userr->get_recovery($recovery_id);


		$this->postmark->to($passwordrecovery['email'], $passwordrecovery['firstname']." ".$passwordrecovery['lastname']);


		$this->postmark->subject('Nodework Password Recovery');

		//@todo make this a real email message
		$message = "Dear Person Paying us $,\n\n";
		$message .= "Your registration is almost complete!  Just follow the link below to finish up this last step!\n\n";
		$message .= site_url('registration/completerecovery/'.$passwordrecovery['key'])."\n\n";
		$message .= "Thank you!\n\n\n\n";
		$message .= "If you did not register for the Illinois State University Passages Portal then please disregard this email and we apologize for the inconvenience.\n";

		$this->postmark->message_plain($message);

		// send the email
		$this->postmark->send();

	}


	public function _email_exists($email){
		$this->load->model('users/userr');

		$this->form_validation->set_message('_email_exists', 'Email Does Not Exist');

		if($this->userr->check_unique_email($email)==FALSE){
			return TRUE;
		}
		return FALSE;
	}

	public function _unique_email($email){
		$this->load->model('users/userr');

		$this->form_validation->set_message('_unique_email', 'Email Already In Use');

		if($this->userr->check_unique_email($email)){
			return TRUE;
		}
		return FALSE;
	}
}

/* End of file Registration.php */
