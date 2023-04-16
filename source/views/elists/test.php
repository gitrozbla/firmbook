<?php
/*
 * lista ulubionych 
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */
/*<h1><i class="fa fa-building-o"></i> <?php echo Yii::t('packages', 'Companies'); ?></h1>*/ 
switch($elist->type) {
	case Elist::TYPE_ELIST:
		$type ='elist';
		//$deleteUrl = 'Yii::app()->controller->createUrl("/elists/remove", array(\'id\'=>$data->item_id, \'type\'=>Elist::TYPE_ELIST))';
		break;
	case Elist::TYPE_FAVORITE:
	default: 	
		$type ='favorite';
		//$deleteUrl = 'Yii::app()->controller->createUrl("/elists/remove", array(\'id\'=>$data->item_id, \'type\'=>Elist::TYPE_FAVORITE))';
		break;
}
?>	
<?php //if($elist->type == Elist::TYPE_ELIST) :?>
<?php $this->widget(
				    'Button',
				    array(
				    	'id' =>'btn-emailModal',
				    	'buttonType' => 'button',
					    //'label' => Yii::t('common','Favorite'),
					    //'type' => 'success',
				    	'icon' => 'fa fa-envelope',
					    'htmlOptions' => array(
						    'data-toggle' => 'modal',
						    'data-target' => '#emailModal',
				    		//'onclick' => 'loadMultiEmailForm(\'emailModal\')',
				    		'style'=>'margin-left: 10px;',
				    		'title' => Yii::t('common', 'Send message'), 				    			    	
				    	),				    	
				    )
			    ); ?>

<div class="clearfix"></div>			            
<?php //endif; ?>			            
<?php $this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(
    	//'id'=>'project-grid-'.uniqid(),
    	'id'=>'grid-'.$type,
    	//'rowCssClassExpression'=>'"items[]_{$data->item_id}"',
    	'selectableRows'=>1,    
    	//'sortableRows'=>true,
    	//'afterSortableUpdate' => 'js:function(id, position){ console.log("id: "+id+", position:"+position);}',	
	    'fixedHeader' => true,
	    'headerOffset' => 0,	    
	    //'type' => 'striped',
    	'dataProvider' => $elist->elistDataProviderUnion(),
	    //'dataProvider' => $elist->elistDataProvider(),
    	//'filter' => $elist,
	    'responsiveTable' => true,
	    //'template' => "{items}",
    	'enableSorting' => true,
    	//'rowCssClassExpression' => '$data->item->active ? "" : "fade-medium";',
    	/*'bulkActions' => array(
    				'actionButtons' => array(
    						array(
    								'id' => 'jakiesid',
    								'buttonType' => 'button',
    								'context' => 'primary',
    								'size' => 'small',
    								'label' => 'Testing Primary Bulk Actions',
    								'click' => 'js:function(values){console.log(values);}'
    						)
    				),
    				// if grid doesn't have a checkbox column type, it will attach
    				// one and this configuration will be part of it
    				'checkBoxColumnConfig' => array(
    					'name' => 'id'
    				),
    	),*/
	    'columns' => array(
	    		array(
	    			'class' => 'CCheckBoxColumn',
	    			'selectableRows' => 2,
	    			'name' => 'id'				
				),
    			array(
    				'name' => 'cache_type',
    				'value' => '"<i class=\"fa fa-".Elist::faIconClass($data["cache_type"])."\"></i>"',
        			'type'  => 'raw',    				
	    			'htmlOptions'=> array(
		    		 	'style'=>'width:50px; text-align: center;',	
		    		)
    			),
	    		array(
	    			'name'=>'name',
	    			'value' => '$data[\'name\']',
	    			'type'  => 'raw',
	    				
	    		),
				array(
    				'name' => 'date',
    				'value' => 'Yii::app()->dateFormatter->format("yyyy-MM-dd", $data[\'date\'])',
        			'type'  => 'raw',    				
	    			'htmlOptions'=> array(
		    		 	'style'=>'width:80px;',	
		    		)
    			),   			
	    )
    ));
    
?>
<?php $script = '$(\'#btn-'.$type.'\').click(function() {			 
			 			$.fn.yiiGridView.update("grid-'.$type.'");
					});'; ?>			            
<?php Yii::app()->clientScript->registerScript('load-grid', $script); ?>

<?php 
$itemId = 32682;
$itemType = 'c';
/*echo '
<script>	
	function loadMultiEmailForm(id) {
 			var checked=$("#grid-'.$type.'").yiiGridView("getChecked","grid-'.$type.'_c0"); // _c0 means the checkboxes are located in the first column, change if you put the checkboxes somewhere else
			
        	var count=checked.length;
            if(count==0){
                alert("No items selected");
 				return false;
            } else
				alert(count);
		
			var values = [];
            checked.each(function(){
            	//values.push($(this).val());
            }); 	
			console.log(values);
			//alert(checked[0]);
			$.ajax({
				method: "POST",
				//method: "GET",
				url: "'.$this->createUrl('site/send_email_to_many').'",
				data: {recipientId:checked'.(isset($itemType) ? ', recipientType:"'.$itemType.'"' : '')
					.', '.Yii::app()->request->csrfTokenName.': "'.Yii::app()->request->csrfToken.'"},
				//data: {type: 1, '.Yii::app()->request->csrfTokenName.': "'.Yii::app()->request->csrfToken.'"},
				dataType: "html",
			}).done(function(html) {	 			      
				$("#" + id).html(html);  
		        //$("#" + id + " .modal-body").html(html);
				$("#" + id).modal("show");						
			});				
	}
</script>
';*/
?>
<?php
Yii::app()->clientScript->registerScript('delete','
$("#btn-emailModal").click(function(){
        var checked=$("#grid-'.$type.'").yiiGridView("getChecked","grid-'.$type.'_c0"); // _c0 means the checkboxes are located in the first column, change if you put the checkboxes somewhere else
        var count=checked.length;
        if(count==0){
			return false;
        	//alert("No items selected");
        }
		
		/*var values = [];
            checked.forEach(function(entry){
				alert(entry);
            	//values.push($(this).val());
            });*/ 
        if(count>0)
        {
			var id = "emailModal";
        	$.ajax({
				method: "POST",
				//method: "GET",
				url: "'.$this->createUrl('site/send_email_to_many').'",
				data: {recipientId:checked'.(isset($itemType) ? ', recipientType:"'.$itemType.'"' : '')
					.', '.Yii::app()->request->csrfTokenName.': "'.Yii::app()->request->csrfToken.'"},
				//data: {type: 1, '.Yii::app()->request->csrfTokenName.': "'.Yii::app()->request->csrfToken.'"},
				dataType: "html",
			}).done(function(html) {	 			      
				$("#" + id).html(html);  
		        //$("#" + id + " .modal-body").html(html);
				$("#" + id).modal("show");						
			});	        
        }
	});
');?>