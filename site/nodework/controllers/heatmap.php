<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Heatmap extends Controller {
	public function __construct() {
		parent::Controller();

		//require a user to be logged in
		if(! is_logged_in()){
			redirect('sessions/create');
		}
	}

	public function build(){
		//start security
		//inherit from controller
		//end security

		$this->load->model('heatmap/heatmaprw');

		parse_str($_SERVER['QUERY_STRING'], $getVars);

		$page = $this->input->xss_clean($getVars['page']);

		//already in unix
		$start_date = $this->input->xss_clean($getVars['start_date']);
		$end_date = $this->input->xss_clean($getVars['end_date']);

		$key = $this->heatmaprw->generate_key();

		
		$parsedurl = parse_url($page);
		if(empty($parsedurl['query'])){
			redirect($page.'?_nwheatmap=true&_nwkey='.$key.'&_nwhost='.urlencode(base_url())."&_nwstart_date=$start_date&_nwend_date=$end_date");
		}else{
			redirect($page.'&_nwheatmap=true&_nwkey='.$key.'&_nwhost='.urlencode(base_url())."&_nwstart_date=$start_date&_nwend_date=$end_date");
		}

	}

	public function clicks(){
		//start security
		//inherit from controller
		//end security

		parse_str($_SERVER['QUERY_STRING'], $getVars);

		$app_id = $this->input->xss_clean($getVars['app_id']);
		$page = $this->input->xss_clean($getVars['page']);
		$key = $this->input->xss_clean($getVars['key']);
		$width = $this->input->xss_clean($getVars['w']);
		$start_date = $this->input->xss_clean($getVars['start_date']);
		$end_date = $this->input->xss_clean($getVars['end_date']);

		if($width == 'null'){
			$width = false;
		}

		$this->load->model('setting/settingr');
		$this->load->model('application/applicationr');
		$this->load->model('heatmap/heatmaprw');

		//require a valid key to generate heatmap
		if(! $this->heatmaprw->validate($key)){
			if(! empty($key)){
				$this->heatmaprw->invalidate_key($key);
			}
			exit;
		}

		$scale = $this->applicationr->application_scale($app_id);
		$clicks = $this->heatmaprw->get_clicks(
			$app_id,
			$page,
			$start_date,
			$end_date
		);

		$data = json_encode(array(
			'clicks' =>$clicks,
			'width' => intval($width), 
			'scale' => $scale
		));
		header('Content-Type: application/json');
		echo "__nw_render_heatmap(".$data.")";
		exit;
	}
}

/* End of file heatmap.php */
