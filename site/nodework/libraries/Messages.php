<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class Messages {
	private $_CI;

	public function __construct(){
		$this->_CI = &get_instance();
	}

	public function report_success($messages){
		$success_messages = $this->get_session_messages('success');

		if(is_array($messages)){
			foreach($messages as $message){
				$success_messages[] = $message;
			}
		}
		else{ //$messages treated as a string
			$success_messages[] = $messages;
		}

		$this->set_session_messages('success', $success_messages);
	}

	public function report_failure($messages){
		$failure_messages = $this->get_session_messages('failure');

		if(is_array($messages)){
			foreach($messages as $message){
				$failure_messages[] = $message;
			}
		}
		else{ //$messages treated as a string
			$failure_messages[] = $messages;
		}

		$this->set_session_messages('failure', $failure_messages);
	}

	public function get_success_messages(){
		$success_messages = $this->get_session_messages('success');
		//clear the array
		$this->set_session_messages('success', array());
		return $success_messages;
	}

	public function get_failure_messages(){
		$failure_messages = $this->get_session_messages('failure');
		//clear the array
		$this->set_session_messages('failure', array());
		return $failure_messages;
	}

///////////// PRIVATE FUNCTIONS

	private function get_session_messages($type){
		$messages = $this->_CI->session->userdata('messages');

		if($messages === FALSE || isset($messages[$type]) === FALSE){
			$messages = array();
		}
		else{
			$messages = $messages[$type];
		}

		return $messages;
	}

	private function set_session_messages($type, $session_messages){
		$messages = $this->_CI->session->userdata('messages');

		$messages[$type] = $session_messages;

		$this->_CI->session->set_userdata('messages', $messages);
	}
}

/* End of file messages.php */
