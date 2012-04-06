<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

/////// APPLICATIONS
$config['applications/create'] = array(
	array(
		'field' => 'name',
		'label' => 'Name',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'domain',
		'label' => 'Domain',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'align',
		'label' => 'Align',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'params',
		'label' => 'Url Parameters',
		'rules' => 'trim|xss_clean'
	)
);

$config['applications/update'] = $config['applications/create'];

////////// USERS
$config['users/create_db'] = array(
	$config['users/create'],
	array(
		'field' => 'db_firstname',
		'label' => 'Firstname',
		'rules' => 'required|xss_clean'
	),
	array(
		'field' => 'db_lastname',
		'label' => 'Lastname',
		'rules' => 'required|xss_clean'
	),
	array(
		'field' => 'db_password',
		'label' => 'Password',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'db_email',
		'label' => 'Email Address',
		'rules' => 'trim|required|xss_clean|valid_email|callback__unique_email'
	),
	array(
		'field' => 'db_confirm_password',
		'label' => 'Password Confirmation',
		'rules' => 'trim|required|xss_clean|matches[db_password]'
	)
);

$config['users/update_db'] = array(
	array(
		'field' => 'db_email',
		'label' => 'Email Address',
		'rules' => 'trim|xss_clean|valid_email'
	),
	array(
		'field' => 'db_permission_group',
		'label' => 'User Level',
		'rules' => 'trim|required|xss_clean'
	)
);

$config['users/profile'] = array(
	array(
		'field' => 'cur_password',
		'label' => 'Current Password',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'new_password',
		'label' => 'New Password',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'confirm_password',
		'label' => 'Password Confirmation',
		'rules' => 'trim|required|xss_clean|matches[new_password]'
	)
);

///////// Registration

$config['registration/passwordrecovery_db'] = array(
	$config['registration/passwordrecovery'],
	array(
		'field' => 'db_email',
		'label' => 'Email Address',
		'rules' => 'trim|required|xss_clean|valid_email|callback__email_exists'
	),
	array(
		'field' => 'db_confirm_email',
		'label' => 'Confirm Email Address',
		'rules' => 'trim|required|xss_clean|valid_email|matches[db_email]'
	),
);
$config['registration/completerecovery_db'] = array(
	$config['registration/completerecovery'],
	array(
		'field' => 'db_password',
		'label' => 'Password',
		'rules' => 'trim|required|xss_clean'
	),
	array(
		'field' => 'db_confirm_password',
		'label' => 'Confirm Password',
		'rules' => 'trim|required|xss_clean|matches[db_password]'
	)
);



///////// SESSIONS
$config['sessions/create'] = array(
	array(
		'field' => 'email',
		'label' => 'Email',
		'rules' => 'trim|required|xss_clean|valid_email|'
	),
	array(
		'field' => 'password',
		'label' => 'Password',
		'rules' => 'trim|required|xss_clean'
	)
);


/* End of file form_validation.php */
