<?php 
/**
 * Mapka google.
 *
 * @copyright (C) 2015
 */ 
?>
<legend>Mapa:</legend>
<?php echo $form->hiddenField($company, 'map_lat');?>
<?php echo $form->hiddenField($company, 'map_lng');?>
<?php $this->widget(
				    'Button',
				    array(				    	
				    	'buttonType' => 'button',
					    'label' => 'Zlokalizuj automatycznie',
					    'type' => 'success',
				    	'icon' => 'fa fa-map-marker',
					    'htmlOptions' => array(						    
				    		'onclick' => 'codeAddress()',
				    		'style'=>'display:block; margin: 0 auto 10px auto;',
				    		'title' => 'Zlokalizuj',					    					    			    	
				    	),				    	
				    )
			    ); ?>
<style>
#map-canvas {
	height: 460px;
	width: 100%;
	margin: 0px;
	padding: 0px
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script>
	var geocoder;
	var map;
	var markers = [];
	
	function initialize() {
		geocoder = new google.maps.Geocoder();	
		<?php if(!isset($company->map_lat) || !isset($company->map_lng)) : ?>
		var mapLatLng =  new google.maps.LatLng(<?php echo Yii::app()->params->google['map']['plLat'];?>,<?php echo Yii::app()->params->google['map']['plLng'];?>);
		var mapZoom = <?php echo Yii::app()->params->google['map']['plZoom'];?>
		<?php else: ?>			
		var mapLatLng =  new google.maps.LatLng(<?php echo $company->map_lat;?>,<?php echo $company->map_lng;?>);
		var mapZoom = <?php echo Yii::app()->params->google['map']['locationZoom'];?>
		<?php endif;?>
		//var locationLatLng =  new google.maps.LatLng(-25.363882,131.044922);
		var mapOptions = {
			zoom: mapZoom,
		    center: mapLatLng
		};
	  	map = new google.maps.Map(document.getElementById('map-canvas'),
	      	mapOptions);

	  	<?php if(isset($company->map_lat) && isset($company->map_lng)) : ?>
	  	addMarker(mapLatLng, true);
	  	<?php else: ?>
		// This event listener will call addMarker() when the map is clicked.
	    google.maps.event.addListener(map, 'click', function(event) {
		  if(!markers.length) {  
		      deleteMarkers();  
		      addMarker(event.latLng, true);
		  }    
	    });
	    <?php endif;?>	  	
	}

	function codeAddress(ifempty=false) {
	  var formCity = document.getElementById('Company_city').value.trim();	
	  var formStreet = document.getElementById('Company_street').value.trim();
	  	
	  if(formCity.length==0 || ifempty && $("#Company_map_lng").val() && $("#Company_map_lng").val())
		return true;
		
	  var address = formCity+' '+formStreet;
	  geocoder.geocode( { 'address': address}, function(results, status) {
	    if (status == google.maps.GeocoderStatus.OK) {
	      map.setCenter(results[0].geometry.location);
	      map.setZoom(<?php echo Yii::app()->params->google['map']['plZoom'];?>);
	      deleteMarkers();
	      addMarker(results[0].geometry.location, true);	      
	    } else {
	      return true;  
	      //alert('Geocode was not successful for the following reason: ' + status);
	    }
	  });
	}

	// Add a marker to the map and push to the array.
	function addMarker(location, draggable=false) {
	  var marker = new google.maps.Marker({
	    position: location,
	    map: map,
	    draggable: draggable
	  });
	  markers.push(marker);
	  if(draggable) {
		  	google.maps.event.addListener(marker,'dragend',function() {  	  
				setFormLatLng(marker.getPosition());     
			});
		}
	  setFormLatLng(marker.getPosition());	
		
	}

	// Sets the map on all markers in the array.
	function setAllMap(map) {
	  for (var i = 0; i < markers.length; i++) {
	    markers[i].setMap(map);
	  }
	}

	// Removes the markers from the map, but keeps them in the array.
	function clearMarkers() {
	  setAllMap(null);
	}

	// Shows any markers currently in the array.
	function showMarkers() {
	  setAllMap(map);
	}

	// Deletes all markers in the array by removing references to them.
	function deleteMarkers() {
	  clearMarkers();
	  markers = [];
	}

	function setFormLatLng(location) {	
		$("#Company_map_lng").attr("value", location.lng());
		$("#Company_map_lat").attr("value", location.lat());	
	}
		
	  
google.maps.event.addDomListener(window, 'load', initialize);

</script>
<div id="map-canvas"></div>

