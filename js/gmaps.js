var mapLoaded = false;
var streetLoaded = false;

// jQuery may be not loaded yet in Creators.
document.addEventListener("DOMContentLoaded", function(){
	// map + marker
	$('a[href="#tab_map"]').on('shown', function(e) {
		if(!mapLoaded) {
			var $target = $('#map-canvas');
			var pos = new google.maps.LatLng($target.attr('data-lat'), $target.attr('data-lng'));

			var map = new google.maps.Map($target[0], {
				zoom: parseInt($target.attr('data-zoom')),
				center: pos
			});

			var marker = new google.maps.Marker({
					position: pos,
					map: map,
					title: $target.attr('data-title')
			});
		}
		mapLoaded = true;
    //google.maps.event.trigger(map, 'resize');
  });

	// street view
	$('a[href="#tab_street"]').on('shown', function(e) {
		if(!streetLoaded) {
			var $target = $('#pano-canvas');

		  var myPano = new google.maps.StreetViewPanorama($target[0], {
		    position: new google.maps.LatLng($target.attr('data-lat'), $target.attr('data-lng'))
		    /*pov: {
		      heading: 165,
		      pitch: 10
		    },
		    //zoom: 4*/
		  });
		  myPano.setVisible(true);
		}
		streetLoaded = true;
  });
});
