<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends Controller {
	public function __construct(){
		parent::Controller();

		//check if logged in already
		if(is_logged_in()){
			redirect('applications/show');
		}
		else{
			redirect('sessions/create');
		}
	}
}

/* End of file welcome.php */
