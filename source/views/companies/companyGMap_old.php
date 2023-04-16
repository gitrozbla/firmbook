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
#map-canvas img {
      	max-width: none;
      }
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script>
var map;
function initialize() {
	var markerLatLng =  new google.maps.LatLng(<?php echo $markerLat; ?>,<?php echo $markerLng; ?>);
	var mapLatLng;
	<?php if(!isset($mapLat) || !isset($mapLng)) : ?>
	mapLatLng = markerLatLng;
	<?php else: ?>
	mapLatLng = new google.maps.LatLng(<?php echo $mapLat; ?>,<?php echo $mapLng; ?>);
	<?php endif;?>
	
	var mapOptions = {
		zoom: <?php echo Yii::app()->params->google['map']['locationZoom'];?>,
	    center: mapLatLng
	};
  	map = new google.maps.Map(document.getElementById('map-canvas'),
      	mapOptions);
  
  	var marker = new google.maps.Marker({
        position: markerLatLng,
        map: map,
        <?php if(isset($markerTitle)) : ?>
        title: '<?php echo $markerTitle; ?>'
        <?php endif;?>    
  	});

  	var panoramaOptions = {
  		    position: markerLatLng,
  		    pov: {
  		      heading: 34,
  		      pitch: 10
  		    }
  		  };
  	var panorama = new google.maps.StreetViewPanorama(document.getElementById('pano'), panoramaOptions);
  	map.setStreetView(panorama);
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>
