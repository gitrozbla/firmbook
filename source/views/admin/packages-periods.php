<?php 
/**
 * Admin - lista okresów.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */ 

if(!isset($creators)) $creators = false;

?>
<h1><i class="fa fa-users"></i> <?php echo Yii::t('packages', 'Periods'); ?></h1>
<?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Add'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('admin/packagesaddperiod'),
			                	'htmlOptions'=> array(
					    		 	'style'=>'float: right; margin-right: 50px; margin-bottom: 10px;',	
					    		)
			                	
			                )
			            ); ?>
<br />			            
<?php $this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(
    	//'id'=>'project-grid',
    	//'rowCssClassExpression'=>'"items[]_{$data->id}"',
    	'selectableRows'=>1,    
    	//'sortableRows'=>true,
    	//'afterSortableUpdate' => 'js:function(id, position){ console.log("id: "+id+", position:"+position);}',	
	    'fixedHeader' => true,
	    'headerOffset' => 0,	    
	    'type' => 'striped',
	    'dataProvider' => PackagePeriod::periodsDataProvider($creators),
	    'responsiveTable' => true,
	    'template' => "{items}",
    	'enableSorting' => false,
	    'columns' => array(	            
    			array(
    				'name'=>'package_id',
    				'value'=>'$data->package->name',    				
    				'htmlOptions'=> array('style'=>'width:150px;')	            	
    			), 
	    		/*array(
	    			'name' => 'creators',
	    			'value' => '"<i class=\"fa fa-".($data->package->creators ? "check-" : "")."square-o\"></i>"',
	    			'type' => 'raw',
	    		),*/
    			array(
    				'name'=>'period',    				
    				'htmlOptions'=> array('style'=>'width:70px;')	            	
    			),	    		
    			'price',
    			array(
				    'class'=>'bootstrap.widgets.TbButtonColumn',
				    'template'=>'{update}&nbsp;&nbsp;&nbsp;{delete}',
	    			/*'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',*/
	    			'buttons' => array(
                    	'delete' => array(
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/packagesremoveperiod/id/$data->package_id/period/$data->period")',
	                        'label' => Yii::t('packages', 'Delete'),
                    		'icon' => 'fa fa-trash-o',
                            /*'ajaxUpdate'=>false,*/                       
	    				),
	    				'update' => array(
	    					'url' => 'Yii::app()->controller->createUrl('
                            . '"/admin/packagesupdateperiod/id/$data->package_id/period/$data->period")',
	                        'label' => Yii::t('packages', 'Edit'),
	    					'icon' => 'fa fa-pencil',
	    				),
	    			),
	    			'htmlOptions'=> array(
		    		 	'style'=>'width:70px; text-align: center;',	
		    		)	
				    
				),	
	               			    		
	    		
	    )
    ));
    
?>
			            

