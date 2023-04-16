<div class="search">
    <div class="row">
        <div class="span3 search-buttons-action">
            <?php 
                $action = $search->action;
                $type = $search->type;
            ?>
            <?php $this->widget(
                'bootstrap.widgets.TbButtonGroup',
                array(
                    'buttons' => array(
                        array(
                            'label' => Yii::t('search',$search->getContextLabel('buy', $type)), 
                            'url' => $search->createUrl(null, array('context'=>$search->getContextOption('buy', $type))),
                            'type' => $action === 'buy' ? 'primary' : 'normal',
                            ),
                        array(
                            'label' => Yii::t('search', $search->getContextLabel('sell', $type)), 
                            'url' => $search->createUrl(null, array('context'=>$search->getContextOption('sell', $type))),
                            'type' => $action === 'sell' ? 'primary' : 'normal',
                            ),
                    ),
                )
            ); ?>
        </div>

        <div class="span3 search-buttons-type">
            <?php $this->widget(
                'bootstrap.widgets.TbButtonGroup',
                array(
                    'buttons' => array(
                        array(
                            'label' => Yii::t('search', 'Products'), 
                            'url' => $search->createUrl(null, array('context'=>$search->getContextOption($action, 'product'))),
                            'type' => $type === 'product' ? 'primary' : 'normal',
                            ),
                        array(
                            'label' => Yii::t('search', 'Services'), 
                            'url' => $search->createUrl(null, array('context'=>$search->getContextOption($action, 'service'))),
                            'type' => $type === 'service' ? 'primary' : 'normal',
                            ),
                        array(
                            'label' => Yii::t('search', 'Companies'), 
                            'url' => $search->createUrl(null, array('context'=>$search->getContextOption($action, 'company'))),
                            'type' => $type === 'company' ? 'primary' : 'normal',
                            ),
                    ),
                )
            ); ?>
        </div>
<?php 

    $class = ucfirst($type);
    switch($type) {
        case 'product':
 
            $controller = 'products';
            break;
        case 'service':
 
            $controller = 'services';
            break;
        case 'company':
 
            $controller = 'companies';
            break;
    }
?>

<?php if (Yii::app()->user->checkAccess(ucfirst($controller).'.add')) : ?>
<div class="span2 search-buttons-type">    
    <?php $this->widget(
        'Button',
        array(
            'label' => Yii::t($type, 'Add '.$type),
            'type' => 'success',
            'icon' => 'fa fa-plus',
        	'url' => $this->createUrl($controller.'/add'),            
            //'disabled' => $addButtonDisabled,
        )
    ); ?> 
</div>   
<?php endif; ?>           
<div class="span3 search-buttons-type">
	<?php /*
    $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'myModal')
    ); ?>
     
    <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Modal header</h4>
    </div>
     
    <div class="modal-body">
    <p>One fine body...</p>
    </div>
     
    <div class="modal-footer">
    <?php $this->widget(
	    'bootstrap.widgets.TbButton',
	    array(
		    'type' => 'primary',
		    'label' => 'Save changes',
		    'url' => '#',
		    'htmlOptions' => array('data-dismiss' => 'modal'),
	    )
    ); ?>
    <?php $this->widget(
	    'bootstrap.widgets.TbButton',
	    array(
		    'label' => 'Close',
		    'url' => '#',
		    'htmlOptions' => array('data-dismiss' => 'modal'),
	    )
    ); ?>
    </div>
     
    <?php $this->endWidget(); ?>
    <?php $this->widget(
	    'bootstrap.widgets.TbButton',
	    array(
		    'label' => 'Click me',
		    'type' => 'primary',
		    'htmlOptions' => array(
			    'data-toggle' => 'modal',
			    'data-target' => '#myModal',
	    	),
	    )
    );
    */?>
    
    <?php /*
    $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'elistModal')
    ); ?>
    dziala
    <?php $this->endWidget(); */?>
    <?php /*
	$this->widget('bootstrap.widgets.TbModal', array(
	    'id' => 'elistModal',	    
	));
	*/?>
	<?php 
    $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'favoriteModal')
    ); ?>
     
    <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo Yii::t('common', 'Favorite'); ?></h4>
    </div>
     
    <div class="modal-body">
    <p>One fine body...</p>
    <?php /*$this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(
    	//'id'=>'project-grid-'.uniqid(),
    	'id'=>'project-grid',
    	'dataProvider' => NULL
    ));
    
	*/?>
		
    </div>
     
    <div class="modal-footer">
    stopka
    </div>
     
    <?php $this->endWidget(); ?>
	<?php 
    $this->beginWidget(
    'bootstrap.widgets.TbModal',
    array('id' => 'elistModal')
    ); ?>
     
    <div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo Yii::t('common', 'Elist'); ?></h4>
    </div>
     
    <div class="modal-body">
    <p>One fine body...</p>
    <?php /*$this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(
    	//'id'=>'project-grid-'.uniqid(),
    	'id'=>'project-grid',
    	'dataProvider' => NULL
    ));
    
	*/?>
		
    </div>
     
    <div class="modal-footer">
    stopka
    </div>
     
    <?php $this->endWidget(); ?>
    <?php /*
    	echo CHtml::ajaxButton(
    		'test ajaxa', 
    		$this->createUrl('elists/favorite'), 
    		array(
    			//'data'=>array('klucz'=>1),
    			'dataType' => 'html',
    			//'dataType' => 'json',
    			///'success' => 'function(data){ 
    			'success' => 'function(html) {
    		
    				//alert(data.header);    				
    				openModal(\'elistModal\', html);
    				//openModal(\'elistModal\', data.header, data.body);
    			}'
    		)
    		//, array('id'=>'modal-button-'.uniqid(), 'live'=>false)
    	);
    	*/
    ?>
    <?php if(!Yii::app()->user->isGuest) {
    	$favoriteCount = Elist::model()->count(
				    		array(
					    		'condition'=>'user_id=:user_id and type=:type',
					    		'params'=>array(':user_id'=>Yii::app()->user->id, ':type'=>Elist::TYPE_FAVORITE)
					    	));   	
    	$this->widget(
		    'Button',
		    array(
		    	'id' =>'btn-favorite',
		    	'buttonType' => 'button',
			    'label' => Yii::t('common','Favorite').' ('.$favoriteCount.')',
			    'type' => 'primary',
		    	'icon' => 'fa fa-heart',
			    'htmlOptions' => array(
				    'data-toggle' => 'modal',
				    'data-target' => '#favoriteModal',
		    		'onclick' => 'loadModalAjax(\'favoriteModal\')',
		    		//'onclick' => 'alert(\'witka\')',
		    	),
		    	/*'ajaxOptions' => array(
		    		'type' => 'POST',
		    		'url' => $this->createUrl('elists/favorite'),
		    		//'data'
		    	)*/
		    )
	    );
    } ?>
    <?php if(!Yii::app()->user->isGuest) {
    	$favoriteCount = Elist::model()->count(
				    		array(
					    		'condition'=>'user_id=:user_id and type=:type',
					    		'params'=>array(':user_id'=>Yii::app()->user->id, ':type'=>Elist::TYPE_ELIST)
					    	));   	
    	$this->widget(
		    'Button',
		    array(
		    	'id' =>'btn-elist',
		    	'buttonType' => 'button',
			    'label' => Yii::t('common','Elist').' ('.$favoriteCount.')',
			    'type' => 'primary',
		    	'icon' => 'fa fa-list',
			    'htmlOptions' => array(
				    'data-toggle' => 'modal',
				    'data-target' => '#elistModal',
		    		'onclick' => 'loadModalAjax(\'elistModal\')',
		    		//'onclick' => 'alert(\'witka\')',
		    	),		    	
		    )
	    );
    } ?>
    <?php 
    /*$this->widget(
    'bootstrap.widgets.TbButton', array(
        'encodeLabel' => false,
        'buttonType' => 'primary',
        'type' => 'danger',
        'label' => 'Delete',
        'htmlOptions' => array(
    		'onclick' => 'js:function() {
                                   $.fn.yiiGridView.update("#project-grid");
                                   
                                   }'
            
        ),
    )
	);*/
	?>
<?php     
/*
$this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'myModal',
    'footer' => implode(' ', array(
        TbHtml::button('Close', array('data-dismiss' => 'modal')),
    )),
));
 
// ajax button to open the modal
echo TbHtml::ajaxButton(
    'Open Modal', // $label
    $this->createUrl('myController/myAction'),  // $url
    array( // $ajaxOptions - https://api.jquery.com/jQuery.ajax/
        // The type of data that you're expecting back from the server.
        'dataType' => 'json', 
 
        // The type of request to make ("POST" or "GET")
        'type' => 'POST',
 
        // Data to be sent to the server.
        'data' => array(
            // you data to be passed
            //'key' => 'value',
        ),
        'beforeSend' => 'function(){
            // Should you want to have a loading widget onClick
            // http://www.yiiframework.com/extension/loading/
            // Loading.show();
        }',
        'success' => 'function(data){
            openModal( "myModal", data.header, data.body);
        }',
        'error' => 'function(xhr, status, error) {
            // this will display the error callback in the modal.
            openModal( "myModal", xhr.status + ' ' +xhr.statusText, xhr.responseText);
        }',
        'complete' => 'function(){
            // hide the loading widget when complete
            // Loading.hide();
        }',
    ),
    array( // $htmlOptions
        // to avoid multiple ajax request
        // http://www.yiiframework.com/wiki/178/how-to-avoid-multiple-ajax-request/
        'id' => 'open-modal-'.uniqid(),
    )
);*/
?>
</div>

        
        <div class="span4 search-simple">
            <?php if (!$search->isAdvanced) : ?>
                <?php require 'searchSimple.php'; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="search-advanced">
        <?php if ($search->isAdvanced) : ?>
            <?php require 'searchAdvanced.php'; ?>
        <?php endif; ?>
    </div>
</div>
<?php 
$str_js = '
	function openModal(id, body) {
	//function openModal(id, header, body) {
		var closeButton = \'<button data-dismiss="modal" class="close" type="button">×</button>\';
 		//var header = "nagłówek";
 		//var body = "zawartosc";
 		//$("#project-grid").remove()
 		$("#" + id + " .modal-header").html( closeButton + \'<h4>Firmy</h4>\');
        //$("#" + id + " .modal-header").html( closeButton + \'<h4>\'+ header + \'</h4>\');
        $("#" + id + " .modal-body").html(body);
		$("#" + id).modal("show");
	}
	
';	
?>
<?php Yii::app()->clientScript->registerScript('show-elist', $str_js); ?>
<?php 
echo '
<script>
	var gridLoaded = false;
	function loadModalAjax(id){
		if(!gridLoaded)
		$.ajax({
			url: "'.$this->createUrl('elists/favorite', array('type')).'",
			dataType: "html",
		}).done(function(html) {
			var closeButton = \'<button data-dismiss="modal" class="close" type="button">×</button>\';
	 		//var header = "nagłówek";
	 		//var body = "zawartosc";
	 		//$("#project-grid").remove()
	 		$("#" + id + " .modal-header").html( closeButton + \'<h4>Firmy</h4>\');
	        //$("#" + id + " .modal-header").html( closeButton + \'<h4>\'+ header + \'</h4>\');
	        $("#" + id + " .modal-body").html(html);
			$("#" + id).modal("show");	
			gridLoaded=true;
		});
		//alert($(\'.modal-header\').html());
		//if(!gridLoaded)
		//alert(\'nieprawda: \'+gridLoaded);
		
	}
</script>	
';
?>