<div id="actual-panel">

<a name="trends-actual"></a>
<div class="section-header">
	Actual Trends
</div>

<div id="browser-trend-img">
	<div class="value" id="browser-trend-chart-div">
		<div class="label bold underline">
			Browser Trends
		</div>

		<?
		if(sizeof($browser_trends) > 2):
		?>
		<table id="browser-trend-chart" class="data">
			<thead>
				<tr>
				<td></td>
				<?
				$step = intval(sizeof($browser_trends) / 3);
				$last = 0;
				?>
				<? for($i = 0; $i < sizeof($browser_trends); $i++): ?>
					<? $day = $browser_trends[$i]; ?>
					<? if($i == $last): ?>
						<? $val = strftime("%m/%d/%Y", $day['date']) ?>
						<? $last += $step; ?>
					<? else: ?>
						<? $val = ''; ?>
					<? endif; ?>
					<th scope="column"><?= $val ?></th>
				<? endfor; ?>
				</tr>
			</thead>

				<?
				$labels=array();
				foreach($browser_trends as $b){
					$keys = array_keys($b);
					foreach($keys as $k){
						if(!in_array($k,$labels)&&$k!='date'){
							$labels[]=$k;
						}
					}
					
				}

				?>
			<tbody>
				<?foreach($labels as $l):?>
					<tr>
						<th scope="row"><?=$l?></th>
						<? foreach($browser_trends as $day): ?>
	
							<? if(isset($day[$l])):?>
								<td>
								<?=$day[$l]?>
								</td>
							<?endif;?>
						<? endforeach; ?>
					</tr>
				<?endforeach;?>
			</tbody>
		</table>
		<? else: ?>
			<div id="no-browser-trend">
				<?= img("nodework/views/static/images/linechartnodata.png") ?>
			</div>
		<? endif; ?>
	</div>
</div>

<div id="platform-trend-img">
	<div class="value" id="platform-trend-chart-div">
		<div class="label bold underline">
			Platform Trends
		</div>

		<?
		if(sizeof($platform_trends) > 2):
		?>
		<table id="platform-trend-chart" class="data">
			<thead>
				<tr>
				<td></td>
				<?
				$step = intval(sizeof($platform_trends) / 3);
				$last = 0;
				?>
				<? for($i = 0; $i < sizeof($platform_trends); $i++): ?>
					<? $day = $platform_trends[$i]; ?>
					<? if($i == $last): ?>
						<? $val = strftime("%m/%d/%Y", $day['date']) ?>
						<? $last += $step; ?>
					<? else: ?>
						<? $val = ''; ?>
					<? endif; ?>
					<th scope="column"><?= $val ?></th>
				<? endfor; ?>
				</tr>
			</thead>
				<?
				$labels=array();
				foreach($platform_trends as $b){
					$keys = array_keys($b);
					foreach($keys as $k){
						if(!in_array($k,$labels)&&$k!='date'){
							$labels[]=$k;
						}
					}
					
				}

				?>
			<tbody>
				<?foreach($labels as $l):?>
					<tr>
						<th scope="row"><?=$l?></th>
						<? foreach($platform_trends as $day): ?>
	
							<? if(isset($day[$l])):?>
								<td>
								<?=$day[$l]?>
								</td>
							<?endif;?>
						<? endforeach; ?>
					</tr>
				<?endforeach;?>
			</tbody>
		</table>
		<? else: ?>
			<div id="no-browser-trend">
				<?= img("nodework/views/static/images/linechartnodata.png") ?>
			</div>
		<? endif; ?>
	</div>
</div>

</div>
