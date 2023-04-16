<?php
/*
 * lista powiadomień 
 *
 * @category views
 * @package alerts
 * @author
 * @copyright (C) 2015
 */

$deleteUrl = 'Yii::app()->controller->createUrl("/alerts/remove", array(\'id\'=>$data["id"]))';
$type = 'alert';
Alert::model()->updateAll(array('displayed'=>1), 'user_id='.Yii::app()->user->id.' and displayed=0');
?>      
<?php $this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(    	
    	'id'=>'grid-'.$type,   		    
	    'dataProvider' => $alert->alertsDataProviderUnion(),    	
	    'responsiveTable' => true,    	
    	'rowCssClassExpression' => '',
    	//'htmlOptions' => array('style' => 'margin-top:0px'),
    	//'itemsCssClass' => 'table table-striped table-hover',
    	'template' => '{items}',    		
    	//'rowCssClassExpression' => '$data->item->active ? "" : "fade-medium";',    	
	    'columns' => array(	    		  			
	    		array(
	    			//'name'=>'context_name',
	    			'value'=>'Alert::getMessage($data)',
	    			//'value'=>'"Błażej dodał firmę Panasonic"',	
	    			//'value' => 'Alert::itemLink($data)',
	    			/*'value' => 'CHtml::link($data[\'name\'], Yii::app()->createUrl("products/show",
    							array("name"=>$data->item->alias)))',*/	    			
	    			'type'  => 'raw',	    	
	    			'headerHtmlOptions'=>array('style'=>'display:none'),
	    		), 
    			array(
				    //'class'=>'bootstrap.widgets.TbButtonColumn',
				    'class'=>'ButtonColumn',
				    'template'=>'{delete}',
	    			/*'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',*/
    				'evaluateHtmlOptions'=>true,
	    			'buttons' => array(
                    	'delete' => array(
	    					'url' => $deleteUrl,
	    					//'Yii::app()->controller->createUrl("/elists/remove")',
                            //. '"/admin/packagesremoveservice/id/$data->item_id")',
	                        'label' => Yii::t('packages', 'Delete'),
    						'options' => array('id' => '$data["id"]'),
	    					'click' => 'function(){	    						
	    						var btnId = $(this).attr(\'id\');	    						
	    						$.fn.yiiGridView.update("grid-'.$type.'", {
	    								dataType:\'json\',
                                        //type:"POST",
                                        url:$(this).attr(\'href\'),
                                        success:function(data) {                                        	
                                        	$.fn.yiiGridView.update("grid-'.$type.'");
                                        	//$("#'.$type.'-btn-"+btnId).removeClass("btn-success");
                                        	$("#btn-'.$type.'").html(data.button);                                        	
                                        }
                                    })
                                    return false;
	    						
	    					}'                                                 
	    				),	    				
	    			),
    				'headerHtmlOptions'=>array('style'=>'display:none'),
	    			'htmlOptions'=> array(
		    		 	'style'=>'width:20px; text-align: center;',	
		    		)					    
				)/*,
				array(
    				'name' => 'date',
    				'value' => 'Yii::app()->dateFormatter->format("yyyy-MM-dd", $data[\'date\'])',
        			'type'  => 'raw',  
					'headerHtmlOptions'=>array('style'=>'display:none'),
	    			'htmlOptions'=> array(
		    		 	'style'=>'width:80px;',	
		    		)
    			), */  			
	    )
    ));
    
?>
<?php $script = '$(\'#btn-'.$type.'\').click(function() {			 
			 			$.fn.yiiGridView.update("grid-'.$type.'");
					});'; ?>			            
<?php Yii::app()->clientScript->registerScript('load-grid', $script); ?>

