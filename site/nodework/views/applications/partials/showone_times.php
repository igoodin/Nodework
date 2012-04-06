<div id="timeday-panel">

<a name="trends-time"></a>

<div class="section-header">
	Time &amp; Day
	<span class="italic nobold">
		(darker/larger means more traffic)
	</span>
</div>

<div id="times-container" class="ui-corner-all">
	<div id="times">
		<? if($times_len > 0): ?>
		<script type="text/javascript">
		$(document).ready(function() {
			var graph = new graphing.chart(
			{

				id:"times",
		
				invertaxis:true,
		
				xaxis: {
					title:"Hours",
					labels: ['12am', '1','2','3','4','5','6','7','8','9','10'
					,'11','12pm','1','2','3','4','5','6','7','8','9','10','11']
				},
		
				yaxis: {
					title:"Days of the Week",
					labels:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]
				},
		
				datasets:
				[{
					label:"hits",
					data:<?= $times ?>
				}]

			});
		});
		</script>
		<? else: ?>
			<?= img("nodework/views/static/images/dots.png") ?>
		<? endif; ?>
	</div>
</div>

</div>
