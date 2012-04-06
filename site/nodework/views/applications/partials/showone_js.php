<script type="text/javascript">
function positionCenter(div){
	div.css("position", "absolute");
	//@todo this might be IE okay
	var pos_left = (jQuery("body").width() / 2) - (div.width() / 2);
	var pos_top = (window.innerHeight / 2) - (div.height() / 2) + jQuery(window).scrollTop();

	div.css("top", pos_top+"px");
	div.css("left", pos_left+"px");
	div.css("z-index", "102");
}

function position_toc(toc, content){
	var w = jQuery(window);
	toc.css("top", jQuery("h2#toc-start").offset().top + 30 + "px");
	toc.css("right", content.offset().left + "px");
}

jQuery(document).ready(function(){
	var toc = jQuery("div#toc");
	var content = jQuery("div#content");
	toc.show();
	position_toc(toc, content);

	toc.hover(
		function(){
			$(this).clearQueue();
			$(this).find("#title").hide();
			$(this).animate({
				width: "160px",
				height: "220px",
				opacity: 0.75
			}, 100,
			function(){
				$(this).find("#toc-content").show();
			});
		},
		function(){
			$(this).clearQueue();
			$(this).find("#toc-content").hide();
			$(this).animate({
				width: "25px",
				height: "100px",
				opacity: 1
			}, 100,
			function(){
				$(this).find("#title").show();
				$(this).find("#toc-content").hide();
			});
		}
	);

	jQuery(window).resize(function(){
		position_toc(toc, content);
	});

	jQuery("button#button-reset-data").click(function(){
		if(confirm("Are you sure?")){
			var app_id = jQuery("input#app_id").val();
			jQuery.ajax({
				type:"POST",
				dataType:"json",
				url:base_url+"applications/data/destroy",
				data:{"app_id":app_id},
				success:function(){
					setTimeout(function(){
						redirect("applications/show/"+app_id);
					}, 300);
				}
			});
		}
	});


	jQuery("button.button-javascript-expand, a.expand").click(function(){
		var t = jQuery(this);
		var tpp = t.parent().parent();
		if(t.text() == "Show"){
			tpp.find(".sh-content").show();
			tpp.find("#js-expand-show").hide();
			tpp.find("#js-expand-hide").show();
		}
		else{
			tpp.find(".sh-content").hide();
			tpp.find("#js-expand-show").show();
			tpp.find("#js-expand-hide").hide();
		}
	});

	jQuery("button#button-show-month-week").click(function(){
		jQuery("table#traffic-report-snapshot tbody").show();
		jQuery(this).hide();
		jQuery("button#button-hide-month-week").show();
	});

	jQuery("button#button-hide-month-week").click(function(){
		jQuery("table#traffic-report-snapshot tbody").hide();
		jQuery(this).hide();
		jQuery("button#button-show-month-week").show();
	});

	jQuery("button#show-all-pages").click(function(){
		var app_id = jQuery("input#app_id").val();
		var div = jQuery("div#blank-content");
		jQuery.get(
			base_url + "applications/pages/<?= $app['application_id'] ?>/<?= $date_filter_start ?>/<?= $date_filter_end ?>",
			function(data){
				div.find("#bc-content").html(data.payload);
				positionCenter(div);
				initTooltips();
				div.show();
			}
		);
	});
	jQuery("button#show-all-referrers").click(function(){
		var app_id = jQuery("input#app_id").val();
		var div = jQuery("div#blank-content");
		jQuery.get(
			base_url + "applications/referrers/<?= $app['application_id'] ?>/<?= $date_filter_start ?>/<?= $date_filter_end ?>",
			function(data){
				div.find("#bc-content").html(data.payload);
				positionCenter(div);
				initTooltips();
				div.show();
			}
		);
	});

	jQuery("button#close-bc").click(function(){
		var div = jQuery("div#blank-content");
		div.hide();
		div.find("#bc-content").html("");
	});

	jQuery("span#change-range-open a").click(function(){
		jQuery("span#change-range-open").hide();
		jQuery("span#change-range-close").show();
		jQuery("#date-range").hide();
		jQuery("#change-form-span").show();
	});
	jQuery("span#change-range-close a").click(function(){
		jQuery("span#change-range-open").show();
		jQuery("span#change-range-close").hide();
		jQuery("#date-range").show();
		jQuery("#change-form-span").hide();
	});

	jQuery("input#start-date, input#end-date").datepicker({
		beforeShow: function(){
			setTimeout(
			   function() {
				 $('#ui-datepicker-div').css('z-index',101);
			   },  100
			);
		}
	});

	jQuery("table#browser-chart").visualize({
		type:"pie",
		height:"250px",
		width:"250px"
	});

	jQuery("table#mobile-chart").visualize({
		type:"pie",
		width:"250px",
		height:"225px"
	});

	jQuery("table#traffic-chart").visualize({
		type:"line",
		width: "600px",
		height: "300px"
	});

	jQuery("table#browser-trend-chart").visualize({
		type:"area",
		width: "600px",
		height: "300px"
	});

	jQuery("table#platform-trend-chart").visualize({
		type:"area",
		width: "600px",
		height: "300px"
	});
	jQuery("table#platform-chart").visualize({
		type:"pie",
		width:"250px",
		height:"250px"
	});

	jQuery("table#resolutions-table").visualize({
		type:"bar",
		width:"250px",
		height:"225px"
	});
});
</script>
