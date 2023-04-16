<?php 
/**
 * Mapa google na listach firm, produktów i usług.
 *
 * @category views
 * @copyright (C) 2015
 */ 
?>

<?php $collapse = $this->beginWidget('bootstrap.widgets.TbCollapse', array(
			//'toggle' => false,			
	)); ?>
	<?php Yii::app()->clientScript->registerScript('check_all', "
			
		var listToggle = false;	
			
		$('.list-toggle-button').on('click', function(e) {
					
			listToggle = !listToggle;
			
	    	$('#item-list input[type=checkbox]').each(function (index, el) {		
			
				if(listToggle)
					this.checked = true;
				else
					this.checked = false;
			
				var id = $(this).attr('id').split('_');	 		 
				var tempmarker
			
				if(id && id[1])
				{
					id = id[1];
					 
					if(markers && markers[id])
					{	 				
						tempmarker = markers[id];
						if(this.checked)	 					
		 					tempmarker.setMap(map);						
						else
							tempmarker.setMap(null);					
					}
				}		
	    	});		
		}); ", CClientScript::POS_READY); ?>

	<?php /*$this->widget(
		            'bootstrap.widgets.TbButton',
		            array(
		            	'id' => 'list-toggle',	
		                //'type' => 'primary',
		                'label' => 'Zaznacz wszystkie',                
		                'htmlOptions' => array(
		                	'style' => 'margin-top:30px'
		                	//'style' => 'display: inline'	
		                //'onclick' => 'toggle();'
		                		
		                		//'$("#item-list input[type=checkbox]").each(function (el) {
									//	       el.checked = !el.checked;
										//    });'	
		                    //'class' => 'search-advanced-button search-expand cool-ajax',
		                ),
		            )
		    		);*/ ?>

	<div class="panel-group" id="accordion">
	  <div class="panel panel-default">
	    <div class="panel-heading">
	    	<br />      
	        <a class="btn btn-default" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
	          <i class="fa fa-map-marker"></i> <?php echo Yii::t('common', 'Show/Hide map') ?>
	        </a>
	        <a class="btn btn-default list-toggle-button" id="list-toggle">
	          <i class="fa fa-map-marker"></i> <?php echo Yii::t('common', 'Show/Hide all') ?>
	        </a>
	        <?php /*$this->widget(
			        'bootstrap.widgets.TbToggleButton',
			        array(
			            'name' => 'testToggleButton',
			        	'enabledLabel' => Yii::t('common', 'Show map'),
			        	'disabledLabel' => Yii::t('common', 'Hide map'),
			        	'value' => false,
			        	'width' => 300,
			            //'onChange' => 'js:function($el, status, e){console.log($el, status, e);}'
			        )
			    ); */ ?>       
	      
	    </div>
	    <div id="collapseOne" class="panel-collapse collapse">
	      <div class="panel-body">	
		
			<style>
			#map-canvas {
				height: 600px;
				width: 100%;
				margin: 10px 0 10px 0;
				padding: 0px;
				/*display: none;*/
			}      
			</style>
			<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
			<script>
			
			var markersTab = [];
			<?php $i = 0; ?>
			
			<?php if($type == 'company') : ?>
				<?php foreach($items as $item) : ?>
					markerTab = [];
					markerTab['lat'] = <?php echo $item->company->map_lat ?>; 
					markerTab['lng'] = <?php echo $item->company->map_lng ?>;
					markerTab['icon'] = '<?php echo Yii::app()->params['google']['map']['markers'][$i++] ?>';
					markerTab['title'] = '<?php echo $item->name ?>';
					markerTab['city'] = '<?php echo $item->company->city ?>';
					markerTab['street'] = '<?php echo $item->company->street ?>';
					markerTab['postcode'] = '<?php echo $item->company->postcode ?>';
					markerTab['phone'] = '<?php echo $item->company->phone ?>';	
					markerTab['url'] = '<?php echo $this->createUrl('companies/show', array(
				                        'name' => $item->alias)) ?>';
					markersTab['<?php echo $item->id ?>'] = markerTab;	
					//markersTab.push(markerTab);
				<?php endforeach; ?>
				
			<?php elseif($type == 'product') : ?>	
				<?php foreach($items as $item) : ?>
					<?php if(isset($item->product->company)) : ?>
						markerTab = [];
						markerTab['lat'] = <?php echo $item->product->company->map_lat ?>; 
						markerTab['lng'] = <?php echo $item->product->company->map_lng ?>;
						markerTab['icon'] = '<?php echo Yii::app()->params['google']['map']['markers'][$i++] ?>';
						markerTab['title'] = '<?php echo $item->name ?>';
						markerTab['city'] = '<?php echo $item->product->company->city ?>';
						markerTab['street'] = '<?php echo $item->product->company->street ?>';
						markerTab['postcode'] = '<?php echo $item->product->company->postcode ?>';
						markerTab['phone'] = '<?php echo $item->product->company->phone ?>';	
						markerTab['url'] = '<?php echo $this->createUrl('products/show', array(
					                        'name' => $item->alias)) ?>';
						markersTab['<?php echo $item->id ?>'] = markerTab;			
					<?php endif; ?>
				<?php endforeach; ?>
				
			<?php elseif($type == 'service') : ?>	
				<?php foreach($items as $item) : ?>
					<?php if(isset($item->service->company)) : ?>
						markerTab = [];
						markerTab['lat'] = <?php echo $item->service->company->map_lat ?>; 
						markerTab['lng'] = <?php echo $item->service->company->map_lng ?>;
						markerTab['icon'] = '<?php echo Yii::app()->params['google']['map']['markers'][$i++] ?>';
						markerTab['title'] = '<?php echo $item->name ?>';
						markerTab['city'] = '<?php echo $item->service->company->city ?>';
						markerTab['street'] = '<?php echo $item->service->company->street ?>';
						markerTab['postcode'] = '<?php echo $item->service->company->postcode ?>';
						markerTab['phone'] = '<?php echo $item->service->company->phone ?>';	
						markerTab['url'] = '<?php echo $this->createUrl('services/show', array(
					                        'name' => $item->alias)) ?>';
						markersTab['<?php echo $item->id ?>'] = markerTab;			
					<?php endif; ?>
				<?php endforeach; ?>	
			<?php endif; ?>
				
			var mapLatLng =  new google.maps.LatLng(<?php echo Yii::app()->params->google['map']['plLat'];?>,<?php echo Yii::app()->params->google['map']['plLng'];?>);
			var mapZoom =<?php echo Yii::app()->params->google['map']['plZoom'];?>;
			var markers = [];
			var map;
			
			function initialize() {
			
				var mapOptions = {
					zoom: mapZoom,
				    center: mapLatLng
				};
				map = new google.maps.Map(document.getElementById('map-canvas'),
				  	mapOptions);	  
				
				for(var key in markersTab)
				{  	
					addMarker(new google.maps.LatLng(markersTab[key]['lat'], markersTab[key]['lng']), key);
					//markers.push(addMarker(new google.maps.LatLng(markersTab[i]['lat'], markersTab[i]['lng'])));
				}	  		  
			}
			
			//Add a marker to the map and push to the array.
			function addMarker(location, id, draggable) {
			
				var contentString = infoContent(id);
				
				var infowindow = new google.maps.InfoWindow({
				      content: contentString
				  });			
				
				var marker = new google.maps.Marker({
					position: location,
					icon: 'http://www.google.com/intl/en_ALL/mapfiles/marker'+markersTab[id]['icon']+'.png',
					//icon: 'http://maps.google.com/mapfiles/kml/paddle/'+markersTab[id]['icon']+'.png',		    
				    title: markersTab[id]['title']	    
				    //draggable: draggable
				  });
			
				google.maps.event.addListener(marker, 'click', function() {
				    infowindow.open(map,marker);
				  });		
				  
				markers[id] = marker;	
			}
			
			function infoContent(id) {
				var contentString = '<address>'
					  + '<strong><a href="' + markersTab[id]['url'] + '">'+markersTab[id]['title'] + '</a></strong><br>'	
					  //+ '<strong>'+markersTab[id]['title'] + '</strong><br>'
					  + markersTab[id]['street'] + '<br>'
					  + markersTab[id]['postcode'] + ', ' + markersTab[id]['city'] + '<br>'
					  + '<abbr title="Phone">P:</abbr> ' + markersTab[id]['phone']
					+ '</address>';
				return contentString;
			}
			
			
			google.maps.event.addDomListener(window, 'load', initialize);
			
			</script>
			<?php $jqScript = '
			 	$("#item-list input.marker").on("click", function() { 	
					
					var id = $(this).attr("id").split("_"); 		 
					var tempmarker
			 		
					if(id && id[1])
					{
						id = id[1];
						 
						if(markers && markers[id])
						{ 				
							tempmarker = markers[id];	
			 		
			 				if(this.checked) 				
			 					tempmarker.setMap(map);					
							else
								tempmarker.setMap(null);				
						}
					}
				});	
			 		
			 	'?>
			<?php Yii::app()->clientScript->registerScript('toggle-marker', $jqScript); ?>
			
			<div id="map-canvas"></div>

			<a class="btn btn-default" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
	          <i class="fa fa-map-marker"></i> <?php echo Yii::t('common', 'Show/Hide map') ?>
	        </a>
	        <a class="btn btn-default list-toggle-button">
	          <i class="fa fa-map-marker"></i> <?php echo Yii::t('common', 'Show/Hide all') ?>
	        </a>

		</div>
    </div>
  </div>  
</div>

<?php $this->endWidget(); ?>