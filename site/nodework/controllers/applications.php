<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applications extends Controller {
	public function __construct(){
		parent::Controller();

		/*
		Require users to be logged in unless in SETUP mode.
		In setup mode login requirement is waved so an initial
		admin user can be created.
		*/
		if(! is_logged_in() && RUNLEVEL != 'SETUP'){
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

		if(! empty($_POST['submit'])){
			$this->load->library('form_validation');
			if($this->form_validation->run('applications/create')){
				$this->load->model('application/applicationw');

				$project = array(
					'application_name' => $this->input->post('name'),
					'application_domain' => $this->input->post('domain'),
					'application_align' => $this->input->post('align')
				);

				$project_id = $this->applicationw->create($project);

				//Creating url exclusion settings in mongo
				$parameters = explode(",",$this->input->post('params'));
				$project_mongo = array(
							'app_id' => (String) $project_id,
							'app_domain' => $this->input->post('domain'),
							'app_params' => $parameters
				);

				$this->applicationw->createsettings($project_mongo);

				$this->messages->report_success("Project Successfully Created!");
				redirect("applications/show/$project_id");
			}
		}

		$this->load->helper('form');
		$this->load->view('header');
		$this->load->view('applications/create');
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

		$app_id = $this->uri->segment(3);

		$this->load->model('application/applicationr');
		if(! empty($_POST['submit'])){
			$this->load->library('form_validation');

			if($this->form_validation->run('applications/create')){
				$this->load->model('application/applicationw');

				$app = array(
					'application_name' => $this->input->post('name'),
					'application_domain' => $this->input->post('domain'),
					'application_align' => $this->input->post('align')
				);

				$this->applicationw->update($app_id, $app);

				//Creating url exclusion settings in mongo
				$parameters = explode(",",$this->input->post('params'));
				$project_mongo = array(
					'app_id' => (String) $app_id,  //'app_id' in mongo is a string
					'app_domain' => $this->input->post('domain'),
					'app_params' => $parameters
				);

				$this->applicationw->updatesettings($app_id,$project_mongo);

				$this->messages->report_success("Project Sucessfully Updated!");

				redirect("applications/show/$app_id");
			}
		}

		$app = $this->applicationr->get($app_id);
		$settings = $this->applicationr->getsettings($app_id);

		$parameters = implode(",",$settings['app_params']);
		$app['application_params']=$parameters;

		$this->load->helper('form');

		$this->load->view('header');
		$data = array(
			'app' => $app,
		);
		$this->load->view('applications/update', $data);
		$this->load->view('footer');
	}

	public function show(){
		//start security
		//inherit from controller
		//end security

		$this->load->model('application/applicationr');
		$this->load->model('nodeinterface/apptraffic');
		$this->load->library('nodestats');
		$this->load->helper('stats');

		$apps = $this->applicationr->get_user_apps(user_id());

		//get app summaries
		$app_ids = array();
		foreach($apps as $a){ $app_ids[] = $a['application_id']; }
		$m_date = $this->nodestats->get_mongo_date(time());
		$summaries = $this->apptraffic->get_summary_snapshot($app_ids, $m_date);
		$summaries = $this->nodestats->process_summaries($summaries, $app_ids);
		$app_len = sizeof($apps);
		for($i = 0; $i < $app_len; $i++){
			$apps[$i]['summary'] = $summaries[$apps[$i]['application_id']];
		}

		$this->load->view('header');
		$data = array('apps' => $apps);
		$this->load->view('applications/show', $data);
		$this->load->view('footer');
	}

	public function manage(){
		//start security
		//inherit from constructor
		if(! is_admin()){
			$this->messages->report_failure('Unable to perform action');
			redirect();
		}
		//end security

		$this->load->model('application/applicationr');

		$apps = $this->applicationr->get_all();

		$this->load->view('header');
		$data = array('apps' => $apps);
		$this->load->view('applications/manage', $data);
		$this->load->view('footer');
	}

	public function manageone(){
		//start security
		//inherit from constructor
		if(! is_admin()){
			$this->messages->report_failure('Unable to perform action');
			redirect();
		}
		//end security

		$app_id = $this->uri->segment(3);

		$this->load->model('application/applicationr');

		$app = $this->applicationr->get($app_id);
		$users = $this->applicationr->get_users($app_id);

		$this->load->view('header');
		$data = array(
			'app' => $app,
			'users' => $users
		);
		$this->load->view('applications/manageone', $data);
		$this->load->view('footer');
	}

	public function showone(){

		/*Imports{{{*/
		$this->load->model('application/applicationr');
		$this->load->model('setting/settingr');
		$this->load->library('nodestats');
		$this->load->library('partial');
		$this->load->helper('application');
		$this->load->helper('stats');

		//Node interfaces
		$this->load->model('nodeinterface/apptraffic');
		$this->load->model('nodeinterface/loc');
		$this->load->model('nodeinterface/pages');
		$this->load->model('nodeinterface/platforms');
		$this->load->model('nodeinterface/browsers');
		$this->load->model('nodeinterface/mobiles');
		$this->load->model('nodeinterface/referrals');
		$this->load->model('nodeinterface/resolutions');
		$this->load->model('nodeinterface/times');/*}}}*/

		$app_id = $this->uri->segment(3);

		//start security
		//inherit from constructor
		$app_perms = $this->applicationr->get_user_app_perms(user_id(), $app_id);
		if(empty($app_perms) && !is_admin()){
			$this->messages->report_failure("You do not have permission to visit this page.");
			redirect();
		}
		//end security

		/*Set Default Vals {{{*/
		parse_str($_SERVER['QUERY_STRING'], $get);

		$set_start_filter = FALSE;
		$set_end_filter = FALSE;
		$date_filter_start = strtotime('-2 weeks');
		$date_filter_end = time();


		//set the date filters
		if(! empty($get['start_date'])){
			$date_filter_start = $this->input->xss_clean($get['start_date']);
			$date_filter_start = strtotime($date_filter_start);
			$set_start_filter = TRUE;
		}

		if(! empty($get['end_date'])){
			$date_filter_end = $this->input->xss_clean($get['end_date']);
			$date_filter_end = strtotime($date_filter_end);
			$set_end_filter = TRUE;
		}

		$app = $this->applicationr->get($app_id);

		$server_loc = $this->settingr->get_setting('server_loc');
		/*}}}*/

		/*Get Raw Data {{{*/
		$app_traffic_snapshot = $this->apptraffic->get_stats_summary(
			$app_id
		);
		$app_traffic_range = $app_traffic_snapshot;

		if($set_start_filter || $set_end_filter){
			$app_traffic_range = $this->apptraffic->get_stats_summary(
				$app_id,
				$date_filter_start,
				$date_filter_end
			);
		}
		$loc = $this->loc->get_stats(
			$app_id,
			$date_filter_start,
			$date_filter_end
		);
		$pages = $this->pages->get_stats_summary(
			$app_id,
			8,
			$date_filter_start,
			$date_filter_end
		);
		$platforms = $this->platforms->get_stats_summary(
			$app_id,
			$date_filter_start,
			$date_filter_end
		);
		$browsers = $this->browsers->get_stats_summary(
			$app_id,
			$date_filter_start,
			$date_filter_end
		);
		$referrals = $this->referrals->get_stats_summary(
			$app_id,
			$limit=8,
			$date_filter_start,
			$date_filter_end
		);
		$resolutions = $this->resolutions->get_stats_summary(
			$app_id,
			$date_filter_start,
			$date_filter_end
		);
		$mobiles = $this->mobiles->get_stats_summary(
			$app_id,
			$date_filter_start,
			$date_filter_end
		);
		$times = $this->times->get_stats_summary(
			$app_id,
			$date_filter_start,
			$date_filter_end
		);
		/*}}}*/

		/*Analyze raw Data {{{*/
		$app_traffic_summary_snapshot = $this->nodestats->calculate_app_traffic_stats($app_traffic_snapshot);
		if($set_start_filter || $set_end_filter){
			$app_traffic_summary_range = $this->nodestats->calculate_app_stats_simple($app_traffic_range);
		}
		$resolutions_summary = $this->nodestats->calculate_resolution_stats($resolutions);
		$pages_summary = $this->nodestats->process_pages($pages);
		$referrer_summary = $this->nodestats->process_referrers($referrals);
		$app_traffic_chart_data = $this->nodestats->calculate_app_chart_data($app_traffic_range,$date_filter_start,$date_filter_end);

		$platform_stats = $this->nodestats->calculate_platform_stats($platforms);
		//platform and mobiles collection share the same format
		$mobile_stats = $this->nodestats->calculate_platform_stats($mobiles);
		$browser_stats = $this->nodestats->calculate_browser_stats($browsers);
		$time_stats = $this->nodestats->calculate_time_stats($times);

		$browser_trends = $this->nodestats->calculate_browser_stats_trend($browsers,$date_filter_start,$date_filter_end);
		$platform_trends = $this->nodestats->calculate_platform_stats_trend($platforms,$date_filter_start,$date_filter_end);
		$locations = $this->nodestats->process_locations($loc);
		/*}}}*/

		/*To View {{{*/
		$data = array(
			'app' => $app,
			'js' => htmlentities(build_javascript($app_id, $server_loc)),
			'app_traffic_snapshot' => $app_traffic_summary_snapshot,
			'app_traffic_range' => isset($app_traffic_summary_range)? $app_traffic_summary_range : FALSE,
			'loc' => $locations,
			'pages' => $pages_summary,
			//'browser_plot' =>$browser_plot,
			'referrals' => $referrer_summary,
			'resolutions' => $resolutions_summary,
			'app_perms' => $app_perms,

			'date_filter_start' => $date_filter_start,
			'date_filter_end' => $date_filter_end,
			'set_start_filter' => $set_start_filter,
			'set_end_filter' => $set_end_filter,

			'platforms' => $platform_stats,
			'mobiles' => $mobile_stats,
			'traffic_chart' => $app_traffic_chart_data,
			'browsers' => $browser_stats,

			'browser_trends'=>$browser_trends,
			'platform_trends'=>$platform_trends,

			'times_len' => sizeof($time_stats),
			'times' => json_encode($time_stats)
		);

		title('Nodework - Show One');

		set_js('js/visualize.jQuery.js');
		set_js('js/markerclusterer_compiled.js');
		set_js('js/graphing.js');
		set_css('css/visualize.css');
		set_css('css/visualize-light.css');

		$this->load->helper('html');

		$this->load->view('header');
		$this->load->view('applications/showone', $data);
		$this->load->view('footer');
		/*}}}*/
	}

	//Destroys the app data & the app itself
	public function destroy(){
		//start security
		//inherit from constructor
		if(! is_admin()){
			$this->messages->report_failure('Unable to perform action');
			redirect();
		}
		//end security

		/* Imports {{{*/
		$this->load->model('application/applicationw');
		$this->load->model('nodeinterface/apptraffic');
		$this->load->model('nodeinterface/loc');
		$this->load->model('nodeinterface/pages');
		$this->load->model('nodeinterface/platforms');
		$this->load->model('nodeinterface/browsers');
		$this->load->model('nodeinterface/referrals');
		$this->load->model('nodeinterface/resolutions');
		$this->load->model('nodeinterface/mobiles');
		$this->load->model('nodeinterface/times');
		/*}}}*/

		$app_id = $this->uri->segment(3);
		if($app_id === FALSE){
			header('Content-Type: application/json');
			echo json_encode(array('stat' => FALSE));
			exit;
		}

		$this->applicationw->delete($app_id);
		$this->applicationw->deletesettings($app_id);
		$this->apptraffic->delete_app($app_id);
		$this->loc->delete_app($app_id);
		$this->pages->delete_app($app_id);
		$this->platforms->delete_app($app_id);
		$this->mobiles->delete_app($app_id);
		$this->browsers->delete_app($app_id);
		$this->referrals->delete_app($app_id);
		$this->resolutions->delete_app($app_id);
		$this->times->delete_app($app_id);

		header('Content-Type: application/json');
		echo json_encode(array('stat' => TRUE));
		exit;
	}

	public function adduser($app_id){
		//start security
		//inherit from constructor
		if(! is_admin()){
			$this->messages->report_failure('Unable to perform action');
			redirect();
		}
		//end security

		if(! isset($_POST['user_id']) || ! isset($_POST['perm'])){
			exit;
		}
		$this->load->model('application/applicationw');

		$user_id = $this->input->post('user_id', TRUE);
		$permission = $this->input->post('perm', TRUE);

		$this->applicationw->add_user($app_id, $user_id, $permission);

		header('Content-Type: application/json');
		echo json_encode(array('stat' => TRUE));
		exit;
	}

	public function removeuser($app_id){
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
		$this->load->model('application/applicationw');

		$user_id = $this->input->post('user_id', TRUE);

		$this->applicationw->remove_user($app_id, $user_id);

		header('Content-Type: application/json');
		echo json_encode(array('stat' => TRUE));
		exit;
	}

	//Destroys the data in an app (but not the app itself)
	public function destroydata(){
		$app_id = $this->input->post('app_id', TRUE);
		if($app_id === FALSE){ exit; }

		//start security
		//inherit from constructor
		$app_perms = $this->applicationr->get_user_app_perms(user_id(), $app_id);
		if( (empty($app_perms) && !is_admin()) || $app_perms['permission'] != 'rw' ){
			$this->messages->report_failure("You do not have permission to visit this page.");
			redirect();
		}
		//end security

		/*Imports {{{*/
		$this->load->model('application/applicationw');
		$this->load->model('nodeinterface/apptraffic');
		$this->load->model('nodeinterface/loc');
		$this->load->model('nodeinterface/pages');
		$this->load->model('nodeinterface/platforms');
		$this->load->model('nodeinterface/browsers');
		$this->load->model('nodeinterface/referrals');
		$this->load->model('nodeinterface/resolutions');
		$this->load->model('nodeinterface/clicks');
		$this->load->model('nodeinterface/mobiles');
		$this->load->model('nodeinterface/times');/*}}}*/

		$this->apptraffic->delete_app($app_id);
		$this->loc->delete_app($app_id);
		$this->pages->delete_app($app_id);
		$this->platforms->delete_app($app_id);
		$this->browsers->delete_app($app_id);
		$this->referrals->delete_app($app_id);
		$this->resolutions->delete_app($app_id);
		$this->clicks->delete_app($app_id);
		$this->mobiles->delete_app($app_id);
		$this->times->delete_app($app_id);

		$this->messages->report_success("Data Successfully Deleted");

		header('Content-Type: application/json');
		echo json_encode(array('stat' => TRUE));
		exit;
	}

	public function adminpanel(){
		//start security
		//inherit from constructor
		if(! is_admin()){
			$this->messages->report_failure('Unable to perform action');
			redirect();
		}
		//end security

		$this->load->view('header');
		$this->load->view('applications/admin');
		$this->load->view('footer');
	}

	public function pages(){
		//start security
		//inherit from constructor
		//end security

		$app_id = $this->uri->segment(3);
		$start_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);

		$this->load->model('nodeinterface/pages');
		$this->load->library('nodestats');

		$pages = $this->pages->get_stats_summary(
			$app_id,
			$limit = FALSE,
			$start_date,
			$end_date
		);
		$pages = $this->nodestats->process_pages($pages, $limit=FALSE);

		$data = array('pages' => $pages);
		$view = $this->load->view('applications/pages', $data, TRUE);

		header('Content-Type: application/json');
		echo json_encode(array('payload' => $view));
		exit;
	}

	public function referrers(){
		//start security
		//inherit from constructor
		//end security

		$app_id = $this->uri->segment(3);
		$start_date = $this->uri->segment(4);
		$end_date = $this->uri->segment(5);

		$this->load->model('nodeinterface/referrals');
		$this->load->library('nodestats');

		$referrers = $this->referrals->get_stats_summary(
			$app_id,
			$limit = FALSE,
			$start_date,
			$end_date
		);

		$referrers = $this->nodestats->process_referrers($referrers, FALSE);

		$data = array('referrals' => $referrers);
		$view = $this->load->view('applications/referrers', $data, TRUE);

		header('Content-Type: application/json');
		echo json_encode(array('payload' => $view));
		exit;
	}
}

/* End of file applications.php */
