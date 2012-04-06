<?php

class test extends Controller {
	public function __construct(){
		parent::Controller();
	}

	public function index(){
		$this->load->model('nodeinterface/appinterface');
		$this->load->helper('node');

		$offset = 'UM6';
		$time = time();

		$date_nix = to_date_nix($time);
		$app_id = "1";

		$snapshot = $this->appinterface->get_traffic_snapshot($app_id, $date_nix);
		$summary = $this->appinterface->get_traffic_summary($app_id, $date_nix-100000, $date_nix);

		printr($summary);
	}
}