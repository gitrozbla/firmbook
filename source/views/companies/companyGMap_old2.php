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
#pano-canvas {
	height: 100%;
	margin: 0px;
	padding: 0px
}
/*#map-canvas img {
      	max-width: none;
      }
#pano-canvas img {
      	max-width: none;
      }*/      
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script>
//var map;
var mapLoaded = false;
var streetLoaded = false;

var markerLatLng =  new google.maps.LatLng(<?php echo $markerLat; ?>,<?php echo $markerLng; ?>);
var mapLatLng;
<?php if(!isset($mapLat) || !isset($mapLng)) : ?>
mapLatLng = markerLatLng;
<?php else: ?>
mapLatLng = new google.maps.LatLng(<?php echo $mapLat; ?>,<?php echo $mapLng; ?>);
<?php endif;?>

$(document).ready(function() {

	$('a[href="#tab_map"]').on('shown', function(e) {
		if(!mapLoaded)
			initializeMap();
		mapLoaded = true;		
        //google.maps.event.trigger(map, 'resize');
    });
    
	$('a[href="#tab_street"]').on('shown', function(e) {	
		if(!streetLoaded)	
        	initializeStreet(); //Call initialize here!
		streetLoaded = true;
    });

});

function initializeMap() {

	var mapOptions = {
			zoom: <?php echo Yii::app()->params->google['map']['locationZoom'];?>,
		    center: mapLatLng
		};
	  	var map = new google.maps.Map(document.getElementById('map-canvas'),
	      	mapOptions);
	  
	  	var marker = new google.maps.Marker({
	        position: markerLatLng,
	        map: map,
	        <?php if(isset($markerTitle)) : ?>
	        title: '<?php echo $markerTitle; ?>'
	        <?php endif;?>    
	  	});
	  		  
}

function initializeStreet() {	
	
	  var panoramaOptions = {
	    position: markerLatLng,
	    /*pov: {
	      heading: 165,
	      pitch: 10
	    },
	    //zoom: 4*/
	  };
	  var myPano = new google.maps.StreetViewPanorama(
	      document.getElementById('pano-canvas'),
	      panoramaOptions);
	  myPano.setVisible(true);
	  
}

//google.maps.event.addDomListener(window, 'load', initialize);

</script>
