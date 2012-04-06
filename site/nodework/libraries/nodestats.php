<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nodestats {
	public function calculate_app_chart_data($app_traffic,$start,$end){
		
		$traffic = array();
		$app_traffic = array_reverse($app_traffic);
		$lastdate=$start;

		foreach($app_traffic as $day){

			$numdays= round(($day['date_nix']-$lastdate) /86400);

			//check to make sure there are no days skipped

			//if the day is actually the next day
			if($numdays>0){
				if($numdays==1){

					$l_arr = array(
						'date' => $day['date_nix'],
						'requests' => 0,
						'visitors' => 0,
						'uniques' => 0
					);
					$lastdate=$day['date_nix'];
				}else{
					//for each day that doesn't have data insert zero values
					while($numdays>1){

						$l_arr = array(
							'date' => $lastdate,
							'requests' => 0,
							'visitors' => 0,
							'uniques' => 0
						);
		
						$traffic[] = $l_arr;
						$lastdate+=86400;
						$numdays-=1;
					}
					$l_arr = array(
						'date' => $day['date_nix'],
						'requests' => 0,
						'visitors' => 0,
						'uniques' => 0
					);
					$lastdate=$day['date_nix'];
				}

				if(isset($day['requests'])){
					$l_arr['requests'] = $day['requests'];
				}
				if(isset($day['visitors'])){
					$l_arr['visitors'] = $day['visitors'];
				}
				if(isset($day['uniques'])){
					$l_arr['uniques'] = $day['uniques'];
				}
				
				$traffic[] = $l_arr;
			}
		}

		//add in empty data if there are blank days in the future
		if($lastdate!=$end){
			$numdays= round(($end-$lastdate) /86400);
			while($numdays>0){
				$lastdate+=86400;
				$l_arr = array(
					'date' => $lastdate,
					'requests' => 0,
					'visitors' => 0,
					'uniques' => 0
				);

				$traffic[] = $l_arr;
				$numdays-=1;
			}
		}

		return $traffic;
	}

	public function calculate_browser_stats_trend($browsers,$start,$end){

		//go through the array and get the labels
		$headers=array();
		foreach($browsers as $b){
			if(!in_array($b['browser'],$headers)){
				$headers[]=$b['browser'];
			}
		}

		$traffic = array();

		$browsers = array_reverse($browsers);


		$numdays= round(abs($start-$end) /86400);

		$result=array();
		$group=array();
		$datelist=array();
		//go through the platfroms and "group" them by date
		for($i=0;$i<sizeof($browsers);$i++){
			$date = $browsers[$i]['date_nix'];
			$group['date_nix']=$date;

			
			if(!in_array($date,$datelist)){
				foreach($headers as $h){
					$group[$h]=0;					
				}
				foreach($browsers as $b){
					if($b['date_nix']==$date){
						foreach($headers as $h){
							if($b['browser']==$h){
								$group[$h]+=$b['count'];	
								
							}				
						}
					}
				}
				$result[]=$group;
				$group=array();
			}
			$datelist[]=$date;
		}
		$browsers=$result;

		$lastdate=$start;

		foreach($browsers as $day){

			$numdays= round(abs($day['date_nix']-$lastdate) /86400);

			//check to make sure there are no days skipped
			if($numdays>0){
				if($numdays==1){

					$l_arr = array(
						'date' => $day['date_nix'],
					);
					foreach($headers as $h){
						$l_arr[$h]=0;					
					}
				}else{
					//for each day that doesn't have data insert zero values
					while($numdays>1){
					
						$l_arr = array(
							'date' => $lastdate,
						);

						foreach($headers as $h){
							$l_arr[$h]=0;					
						}
						$lastdate+=86400;	
						$traffic[] = $l_arr;
						$numdays-=1;
					}
					$l_arr = array(
						'date' => $day['date_nix'],
					);
					foreach($headers as $h){
						$l_arr[$h]=0;					
					}
				}

				foreach($headers as $h){
					$l_arr[$h]=$day[$h];
				}
		
				$traffic[] = $l_arr;
				$lastdate=$day['date_nix'];
			}
		}

		//add in empty data if there are blank days in the future
		if($lastdate!=$end){
			$numdays= round(($end-$lastdate) /86400);
			while($numdays>0){
				$lastdate+=86400;
				$l_arr = array(
					'date' => $lastdate,
				);

				foreach($headers as $h){
					$l_arr[$h]=0;					
				}
				$traffic[] = $l_arr;
				$numdays-=1;
			}
		}


		//convert counts to percent
		$total=0.0;
		$i=0;
		
		foreach($traffic as $t){
			foreach($headers as $h){
				$total+=$t[$h];			
			}
			
			foreach($headers as $h){
				if($total>0){
					$traffic[$i][$h]=round($traffic[$i][$h]*100/$total)/100;
				}
			}		
			$total=0;
			$i++;
		} 

		return $traffic;
	}

	public function calculate_platform_stats_trend($platforms,$start,$end){

		//get all the titles from the array
		$headers=array();
		foreach($platforms as $b){
			if(!in_array($b['platform'],$headers)){
				$headers[]=$b['platform'];
			}
		}

		$traffic = array();

		$platforms = array_reverse($platforms);

		$numdays= round(abs($start-$end) /86400);

		
		$result=array();
		$group=array();
		$datelist=array();

		//go through the platfroms and "group" them by date
		for($i=0;$i<sizeof($platforms);$i++){
			$date = $platforms[$i]['date_nix'];
			$group['date_nix']=$date;

			
			if(!in_array($date,$datelist)){
				foreach($headers as $h){
					$group[$h]=0;					
				}
				foreach($platforms as $b){
					if($b['date_nix']==$date){
						foreach($headers as $h){
							if($b['platform']==$h){
								$group[$h]+=$b['count'];	
								
							}				
						}
					}
				}
				$result[]=$group;
				$group=array();
			}
			$datelist[]=$date;
		}
		$platforms=$result;

		$lastdate=$start;
		//foreach day add it to the array
		foreach($platforms as $day){

			$numdays= round(abs($day['date_nix']-$lastdate) /86400);

			//check to make sure there are no days skipped
			if($numdays>0){
				if($numdays==1){

					$l_arr = array(
						'date' => $day['date_nix'],
					);
					foreach($headers as $h){
						$l_arr[$h]=0;					
					}
				}else{
					//for each day that doesn't have data insert zero values
					while($numdays>1){
					
						$l_arr = array(
							'date' => $lastdate,
						);

						foreach($headers as $h){
							$l_arr[$h]=0;					
						}
						$lastdate+=86400;	
						$traffic[] = $l_arr;
						$numdays-=1;
					}
					$l_arr = array(
						'date' => $day['date_nix'],
					);
					foreach($headers as $h){
						$l_arr[$h]=0;					
					}
				}

				foreach($headers as $h){
					$l_arr[$h]=$day[$h];
				}
		
				$traffic[] = $l_arr;
				$lastdate=$day['date_nix'];
			}
		}

		//add in empty data if there are blank days in the future
		if($lastdate!=$end){
			$numdays= round(($end-$lastdate) /86400);
			while($numdays>0){
				$lastdate+=86400;
				$l_arr = array(
					'date' => $lastdate,
				);

				foreach($headers as $h){
					$l_arr[$h]=0;					
				}
				$traffic[] = $l_arr;
				$numdays-=1;
			}
		}


		//convert counts to percent
		$total=0.0;
		$i=0;
		
		foreach($traffic as $t){
			foreach($headers as $h){
				$total+=$t[$h];			
			}
			
			foreach($headers as $h){
				if($total>0){
					$traffic[$i][$h]=round($traffic[$i][$h]*100/$total)/100;
				}
			}		
			$total=0;
			$i++;
		} 

		return $traffic;
	}


	public function calculate_platform_stats($platforms){
		$platform_array = array();
		$total_count = 0;

		foreach($platforms as $day){
			if(isset($platform_array[$day['platform']])){
				$platform_array[$day['platform']] += $day['count'];
			}
			else{
				$platform_array[$day['platform']] = $day['count'];
			}

			$total_count += $day['count'];
		}

		$total_count = floatval($total_count);
		foreach($platform_array as $platform){
			if($platform / $total_count <= 0.01){ //reject if pct <= 1%
				unset($platform_array[$platform]);
			}
		}

		return $platform_array;
	}

	public function calculate_time_stats($times){
		$ret_times = array();
		foreach($times as $day){
			if(isset($ret_times[$day['eventat']])){

				$ret_times[$day['eventat']]['count'] += $day['count'];
			}
			else{
				$ret_times[$day['eventat']] = $day;
			}
			$event_arr = explode('::', $day['eventat']);
			$ret_times[$day['eventat']]['day'] = $event_arr[0];
			$ret_times[$day['eventat']]['hour'] = $event_arr[1];
		}
		$ret_times = array_values($ret_times);

		$max = 0;
		foreach($ret_times as $time){
			if($time['count'] > $max){
				$max = $time['count'];
			}
		}
		$max = (float) $max;

		$times_len = sizeof($ret_times);
		$max_pix = 200.0;
		for($i = 0; $i < $times_len; $i++){
			$ret_times[$i]['z'] = intval(($ret_times[$i]['count'] / $max) * $max_pix);
			unset($ret_times[$i]['date_nix']);
			unset($ret_times[$i]['app_id']);
			unset($ret_times[$i]['count']);
			unset($ret_times[$i]['eventat']);
			unset($ret_times[$i]['_id']);

		}

		$i=0;
		$final=array();
		foreach($ret_times as $t){
			$list=array();
			$list[0]=$t['day'];
			$list[1]=$t['hour'];
			$list[2]=$t['z'];

			$final[$i]=$list;
			$i++;
		}

		return $final;
	}

	public function calculate_app_traffic_stats($app_traffic){
		$e_array = array(
			'uniques' => 0,
			'visitors' => 0,
			'requests' => 0
		);

		if(empty($app_traffic)){

			return array(
				'today' => $e_array,
				'week' => $e_array,
				'month' => $e_array
			);
		}
		$ret = array();

		//we do this by today, 7 days, and 30 days

		//look for today
		$today = $app_traffic[0];
		if($this->is_today($today['date'])){
			$ret['today'] = array(
				'uniques' => isset($today['uniques']) ? $today['uniques'] : 0,
				'visitors' => isset($today['visitors']) ? $today['visitors'] : 0,
				'requests' => isset($today['requests']) ? $today['requests'] : 0
			);
		}
		else{
			$ret['today'] = $e_array;
		}

		//look up this week
		$week_uniques = 0;
		$week_visitors = 0;
		$week_requests = 0;
		for($i=0; $i<7; $i++){
			if(isset($app_traffic[$i]) && $this->is_this_week($app_traffic[$i]['date'])){
				$day = $app_traffic[$i];
				$week_uniques += isset($day['uniques']) ? $day['uniques'] : 0;
				$week_visitors += isset($day['visitors']) ? $day['visitors'] : 0;
				$week_requests += isset($day['requests']) ? $day['requests'] : 0;
			}
		}
		$ret['week'] = array(
			'uniques' => $week_uniques,
			'visitors' => $week_visitors,
			'requests' => $week_requests
		);

		//look up this month
		$month_uniques = 0;
		$month_visitors = 0;
		$month_requests = 0;
		for($i=0; $i<30; $i++){
			if(isset($app_traffic[$i]) && $this->is_this_month($app_traffic[$i]['date'])){
				$day = $app_traffic[$i];
				$month_uniques += isset($day['uniques']) ? $day['uniques'] : 0;
				$month_visitors += isset($day['visitors']) ? $day['visitors'] : 0;
				$month_requests += isset($day['requests']) ? $day['requests'] : 0;
			}
		}

		$ret['month'] = array(
			'uniques' => $month_uniques,
			'visitors' => $month_visitors,
			'requests' => $month_requests
		);
		return $ret;
	}

	public function calculate_app_stats_simple($app_traffic){
		$total_traffic = array(
			'requests' => 0,
			'uniques' => 0,
			'visitors' => 0
		);

		foreach($app_traffic as $day){
			$total_traffic['requests'] += isset($day['requests']) ? $day['requests'] : 0;
			$total_traffic['uniques'] += isset($day['uniques']) ? $day['uniques'] : 0;
			$total_traffic['visitors'] += isset($day['visitors']) ? $day['visitors'] : 0;
		}

		return $total_traffic;
	}

	public function is_today($date_str){
		if($date_str == $this->get_mongo_date(time())){
			return TRUE;
		}
		return FALSE;
	}

	public function is_this_week($date_str){
		for($i=0; $i<7; $i++){
			if($date_str == $this->get_mongo_date(time()-(60*60*24*$i))){
				return TRUE;
			}
		}
		return FALSE;
	}

	public function is_this_month($date_str){
		for($i=0; $i<30; $i++){
			if($date_str == $this->get_mongo_date(time()-(60*60*24*$i))){
				return TRUE;
			}
		}
		return FALSE;

	}

	public function get_mongo_date($timestamp){
		$year = strftime("%Y", $timestamp);
		$day_of_year = preg_replace("/^0+/", '', strftime("%j", $timestamp));

		return "$year$day_of_year";
	}

	public function calculate_resolution_stats($resolutions){
		if(empty($resolutions)){
			return array();
		}

		$ret_res = array();
		$tot_res = 0;
		foreach($resolutions as $day){
			if(isset($ret_res[$day['res']])){
				$ret_res[$day['res']]['count'] += $day['count'];
			}
			else{
				$ret_res[$day['res']] = $day;
			}
			$tot_res += $day['count'];
		}

		unset($resolutions);

		$count = array();
		foreach($ret_res as $key=>$value){ $count[$key] = $value['count']; }
		array_multisort($count, SORT_DESC, $ret_res);
		unset($count);

		$ret_res = array_values($ret_res);

		$res_len = sizeof($ret_res);
		$tot_pct = 0;
		$resize = FALSE;
		if($res_len > 5){ //we only want to display the top 5
			$resize = TRUE;
			$ret_res = array_slice($ret_res, 0, 5);
			$res_len = 5;
		}
		for($i = 0; $i < $res_len; $i++){
			$pct = intval(100.0 * ($ret_res[$i]['count'] / $tot_res));
			$ret_res[$i]['percent'] = $pct;
			$tot_pct += $pct;
		}
		if($resize){
			$ret_res[] = array(
				'res' => 'Other',
				'percent' => intval(100.0 - $tot_pct)
			);
		}

		return $ret_res;
	}

	public function process_pages($pages, $limit=TRUE){
		$pages_len = sizeof($pages);
		$total_pages = 0;

		$ps = array();
		for($i = 0; $i < $pages_len; $i++){
			$total_pages += $pages[$i]['count'];
			unset($pages[$i]['date_nix']);
			if(isset($ps[$pages[$i]['page']])){
				$ps[$pages[$i]['page']]['count'] += $pages[$i]['count'];
			}
			else{
				$ps[$pages[$i]['page']] = $pages[$i];
			}
		}
		unset($pages);

		$count = array();
		foreach($ps as $key=>$value){ $count[$key] = $value['count']; }
		array_multisort($count, SORT_DESC, $ps);
		unset($count);

		$ps = array_values($ps);
		$ps_len = sizeof($ps);

		for($i = 0; $i < $ps_len; $i++){
			$ps[$i]['pct'] = intval(100.0 * ($ps[$i]['count'] / $total_pages));
			$url = parse_url($ps[$i]['page']);
			$ps[$i]['fullpage'] = $ps[$i]['page'];
			$ps[$i]['page'] = $url['path'];
			if(! empty($url['query'])){
				$ps[$i]['page'] .= "?{$url['query']}";
			}
			if(! empty($url['fragment'])){
				$ps[$i]['page'] .= "#{$url['fragment']}";
			}

			if($ps[$i]['page'] == '/'){
				$ps[$i]['page'] = 'HOMEPAGE';
			}
			elseif(strlen($ps[$i]['page']) > 22){
				$p = $ps[$i]['page'];
				$ps[$i]['page'] = substr($p,0,11).'...'.substr($p, 20, 8);
			}
		}

		if($limit){
			return array_slice($ps, 0, 10);
		}
		else{
			return $ps;
		}
	}

	public function process_referrers($referrers, $limit=TRUE){
		$referrers_len = sizeof($referrers);
		$total_referrers = 0;

		$refs = array();
		for($i = 0; $i < $referrers_len; $i++){
			$total_referrers += $referrers[$i]['count'];
			unset($referrers[$i]['date_nix']);
			if(isset($refs[$referrers[$i]['ref']])){
				$refs[$referrers[$i]['ref']]['count'] += $referrers[$i]['count'];
			}
			else{
				$refs[$referrers[$i]['ref']] = $referrers[$i];
			}
		}
		unset($referrers);

		$count = array();
		foreach($refs as $key=>$value){ $count[$key] = $value['count']; }
		array_multisort($count, SORT_DESC, $refs);
		unset($count);

		$refs = array_values($refs);
		$refs_len = sizeof($refs);

		for($i = 0; $i < $refs_len; $i++){
			$refs[$i]['pct'] = intval(100.0 * ($refs[$i]['count'] / $total_referrers));
			$refs[$i]['ref'] = preg_replace('/^www\./', '', $refs[$i]['ref']);
			if(strlen($refs[$i]['ref']) > 25){
				$sub1 = substr($refs[$i]['ref'], 0, 13);
				$sub2 = substr($refs[$i]['ref'], strlen($refs[$i]['ref'])-7, 7);
				$refs[$i]['host'] = "$sub1...$sub2";
			}
		}

		if($limit){
			return array_slice($refs, 0, 10);
		}
		else{
			return $refs;
		}
	}

	public function calculate_browser_stats($browsers){
		$total_count = 0;
		$b_names = array();
		foreach($browsers as $day){
			if(isset($b_names[$day['browser']])){
				$b_names[$day['browser']] += $day['count'];
			}
			else{
				$b_names[$day['browser']] = $day['count'];
			}

			$total_count += $day['count'];
		}

		$total_count = floatval($total_count);
		foreach($b_names as $b){
			if($b / $total_count <= 0.01){ // reject if pct <= 1%
				unset($b_names[$b]);
			}
		}

		return $b_names;
	}

	//@todo this function is currently unused, might want it in the future
	public function get_browser_text_stats($browsers){
		$total = 0;
		$text = '';
		foreach($browsers as $b){$total += $b['count'];}
		$total = floatval($total);
		$len = sizeof($browsers);
		for($i = 0; $i < $len; $i++){
			$b = $browsers[$i];
			$pct = round(100.0 * $b['count'] / $total);
			if($pct <= 1){ //reject if pct <= 1%
				continue;
			}
			$text .= "{$b['browser']}: $pct%";
			if($i < $len - 1){
				$text .= '<br />';
			}
		}

		return $text;
	}

	public function process_locations($loc){
		$locations = array();
		foreach($loc as $day){
			$locations[] = array(
				$day['lat'],
				$day['long']
			);
		}

		return json_encode($locations);
	}

	public function process_summaries($summaries, $app_ids){
		$tmp_summaries = array();

		foreach($app_ids as $id){
			$summ = array();
			foreach($summaries as $s){
				if($s['app_id'] == $id){
					$summ = $s;
					break;
				}
			}
			if(empty($summ)){
				$tmp_summaries[$id] = array(
					'uniques' => 0,
					'visitors' => 0,
					'requests' => 0
				);
			}
			else{
				$tmp_summaries[$id] = array(
					'uniques' => isset($summ['uniques']) ? $summ['uniques'] : 0,
					'requests' => isset($summ['requests']) ? $summ['requests'] : 0,
					'visitors' => isset($summ['visitors']) ? $summ['visitors'] : 0
				);
			}
		}

		return $tmp_summaries;
	}

	//@todo this function is currently unused - might want it in the future
	public function get_platform_text_stats($platforms){
		$total = 0;
		$text = '';
		foreach($platforms as $p){$total += $p['count'];}
		$len = sizeof($platforms);
		for($i = 0; $i < $len; $i++){
			$p = $platforms[$i];
			$pct = round(100.0 * $p['count'] / $total);
			if($pct <= 1){ //reject if pct <= 1%
				continue;
			}
			$text .= "{$p['platform']}: $pct%";
			if($i < $len - 1){
				$text .= '<br />';
			}
		}

		return $text;
	}
}

/* End of file nodestats.php */
