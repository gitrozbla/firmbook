<?php 
/**
 * Mapka google.
 *
 * @category views
 * @copyright (C) 2015
 */ 
?>

<style>
#map-canvas {
	height: 100%;
	margin: 0px;
	padding: 0px
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script>
var map;
function initialize() {
	var bryantPark = new google.maps.LatLng(37.869260, -122.254811);
	  var panoramaOptions = {
	    position: bryantPark,
	    pov: {
	      heading: 165,
	      pitch: 0
	    },
	    zoom: 1
	  };
	  var myPano = new google.maps.StreetViewPanorama(
	      document.getElementById('map-canvas'),
	      panoramaOptions);
	  myPano.setVisible(true);
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>
