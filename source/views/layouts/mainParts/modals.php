		
            <?php 
                $this->beginWidget(
                'bootstrap.widgets.TbModal',
                array('id' => 'favoriteModal')
                ); ?>     
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4><i class="fa fa-heart"></i> <?php echo Yii::t('elists', 'Favorite'); ?></h4>
                    </div>     
                    <div class="modal-body"></div>     
                    <div class="modal-footer"></div>     
                <?php $this->endWidget(); ?>

                <?php 
                $this->beginWidget(
                'bootstrap.widgets.TbModal',
                array('id' => 'elistModal')
                ); ?>     
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4><i class="fa fa-list"></i> <?php echo Yii::t('elists', 'Elist'); ?></h4>
                    </div>     
                    <div class="modal-body"></div>     
                    <div class="modal-footer"></div>     
                <?php $this->endWidget(); ?>

                <?php 
                $this->beginWidget(
                'bootstrap.widgets.TbModal',
                array('id' => 'followModal')
                ); ?>     
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4><i class="fa fa-eye"></i> <?php echo Yii::t('follow', 'Observed'); ?></h4>
                    </div>     
                    <div class="modal-body"></div>     
                    <div class="modal-footer"></div>     
                <?php $this->endWidget(); ?>
                 <?php 
                $this->beginWidget(
                'bootstrap.widgets.TbModal',
                array('id' => 'alertModal')
                ); ?>     
                    <div class="modal-header">
                        <a class="close" data-dismiss="modal">&times;</a>
                        <h4><i class="fa fa-bell"></i> <?php echo Yii::t('alerts', 'Notifications'); ?></h4>
                    </div>     
                    <div class="modal-body"></div>     
                    <div class="modal-footer"></div>     
                <?php $this->endWidget(); ?>  
               
		
		
<?php 
echo '
<script>
	var favoriteLoaded = false;
	var elistLoaded = false;
	var followLoaded = false;
	var alertLoaded = false;		
	var jsload;
	function loadElist(id, elistType){
		jsload = false;		
		if(id==\'favoriteModal\' && !favoriteLoaded || id==\'elistModal\' && !elistLoaded)
		{
			if(!favoriteLoaded && !elistLoaded && !followLoaded && !alertLoaded)
				jsload = true;	
		
			$.ajax({
				method: "GET",
				url: "'.$this->createUrl('elists/show').'",
				data: {type: elistType, jsload: jsload},
				dataType: "html",
			}).done(function(html) {
				var closeButton = \'<button data-dismiss="modal" class="close" type="button">×</button>\';	 		
		 		//$("#" + id + " .modal-header").html( closeButton + \'<h4>Firmy</h4>\');	        
		        $("#" + id + " .modal-body").html(html);
				$("#" + id).modal("show");			
				if(id==\'favoriteModal\')
					favoriteLoaded = true;
				else if(id==\'elistModal\')
					elistLoaded = true;			
			});
		}		
	}
</script>
';
?>
<?php 
echo '
<script>
	function loadFollow(){
		jsload = false;		
		var id = "followModal";
		if(!followLoaded) {
			if(!favoriteLoaded && !elistLoaded && !followLoaded && !alertLoaded)
				jsload = true;	
		
			$.ajax({
				method: "GET",
				url: "'.$this->createUrl('follow/show').'",
				data: {jsload: jsload},
				dataType: "html",
			}).done(function(html) {	 			        
		        $("#" + id + " .modal-body").html(html);
				$("#" + id).modal("show");                					
                followLoaded = true;									
			});
		}		
	}
</script>
';
?>
<?php 
echo '
<script>	
	function loadAlert(){
		jsload = false;		
		var id = "alertModal";
		if(!alertLoaded) {
			if(!favoriteLoaded && !elistLoaded && !followLoaded && !alertLoaded)
				jsload = true;	
		
			$.ajax({
				method: "GET",
				url: "'.$this->createUrl('alerts/show').'",
				data: {jsload: jsload},
				dataType: "html",
			}).done(function(html) {	 			        
		        $("#" + id + " .modal-body").html(html);
				$("#" + id).modal("show");
                $("#btn-alerts").removeClass("btn-danger").html(\'<i class="fa fa-bell"></i> (0)\');
                alertLoaded = true;									
			});
		}		
	}
</script>
';
?>
<?php $this->beginWidget('bootstrap.widgets.TbModal',
		array(
				'id' => 'emailModal',
				'htmlOptions' => array(
					'class'=>'text-left'
				)
		)
	); 
?>	
<?php $this->endWidget(); ?>			    
