<div id="geo-panel">

<a name="trends-geo"></a>
<div class="section-header">
	Geographic Distribution
</div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">
	var initialLocation =new google.maps.LatLng(40.48,-88.93);
	var bloomington = new google.maps.LatLng(40.48,-88.93);
	var map;
	var infowindow = new google.maps.InfoWindow();

	function initialize() {
		var myOptions = {
			zoom: 5,
			scrollwheel: false,
			streetViewControl: false,
			mapTypeControlOptions: {
				mapTypeIds: []
			},
			mapTypeId: 'clean'
		};
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

		var style =[
			{
			  featureType: "road",
			  elementType: "all",
			  stylers: [{ visibility: "off"}]
			},
			{
			  featureType: "poi",
			  elementType: "all",
			  stylers: [{ visibility: "off"}]
			},
			{
			  featureType: "transit",
			  elementType: "all",
			  stylers: [{ visibility: "off"}]
			}
		];
		var styledMapOptions = {
			name: "Clean"
		}
		var custom = new google.maps.StyledMapType(style, styledMapOptions);
		map.mapTypes.set('clean', custom);
		map.setMapTypeId('clean');

		map.setCenter(initialLocation);
		infowindow.setPosition(initialLocation);
		infowindow.open(map);

		//get locations and plot them as markers
		var locations =<?= $loc ?>;

		var markers = [];
		for(coord in locations){
			var myLatlng = new google.maps.LatLng(locations[coord][0],locations[coord][1]);
			var marker = new google.maps.Marker({position: myLatlng,map: map});
			markers.push(marker);
		}
		var mc = new MarkerClusterer(map,markers);
	}

	setTimeout("initialize()", 100);
</script>

<div id="map-container" class="ui-corner-all">
	<div id="map_canvas"></div>
</div>

</div>
