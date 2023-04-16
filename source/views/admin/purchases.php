<?php 
/**
 * Admin - historia zmian pakietów.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */
 
?>
<h1><i class="fa fa-building-o"></i> <?php echo Yii::t('packages', 'Package change history:'); ?></h1>
<?php $this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(
    	//'id'=>'project-grid-'.uniqid(),
    	'id'=>'project-grid',
    	//'rowCssClassExpression'=>'"items[]_{$data->item_id}"',
    	'selectableRows'=>1,    
    	//'sortableRows'=>true,
    	//'afterSortableUpdate' => 'js:function(id, position){ console.log("id: "+id+", position:"+position);}',	
	    'fixedHeader' => true,
	    'headerOffset' => 0,	    
	    //'type' => 'striped',
	    'dataProvider' => $purchase->adminSearch(),
    	//'filter' => $purchase,
	    'responsiveTable' => true,
	    //'template' => "{items}",
    	'enableSorting' => true,
    	'rowCssClassExpression' => '$data->status == Package::$_packagePurchaseStatus["PURCHASE_STATUS_EXPIRED"] ? "fade-medium" : "";',
	    'columns' => array(	            
    			array(
    				'name'=>'id',    				
    				'htmlOptions'=> array('style'=>'width:60px;')	            	
    			),
	    		array(
	    			'name'=>'package_id',
	    			'value' => 'Package::badge($data->package->name, $data->package->css_name)." / ".$data->period',
	    			'type'=>'raw',
	    			'htmlOptions'=> array('style'=>'width:130px;')
	    		),
	    		array(
	    			'name'=>'price',
	    			'htmlOptions'=> array('style'=>'width:60px;')
	    		),	    		
	    		array(
	    			'name'=>'status', 
	    			'value'=>'Package::statusToLabel($data->status, $data->paid)',
	    			'htmlOptions'=> array('style'=>'width:120px;')	
				),
	    		array(
	    				'name'=>'date_start',
	    				'value'=>'Yii::app()->dateFormatter->format("yyyy-MM-dd", $data->date_start)',
	    				'htmlOptions'=> array('style'=>'width:90px;')
	    		),
	    		array(
	    			'name'=>'date_expire', 
	    			'value'=>'Yii::app()->dateFormatter->format("yyyy-MM-dd", $data->date_expire)',
	    			'htmlOptions'=> array('style'=>'width:90px;')	
	    		),
	    		array(
	    			'name'=>'user_username',
	    			'value' => '$data->user->username',
	    			//'htmlOptions'=> array('style'=>'width:120px;')
	    		),
    			/*array(
    				'name'=>'package_name',
    				'value' => '$data->package->name',    				
    				//'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),*/
    			array(
    				'name'=>'date_added',
    				'value' => '$data->date_added',    				
    				'htmlOptions'=> array('style'=>'width:95px;')	            	
    			), 
	    		array(
	    				'template' => '{pay}&nbsp;&nbsp;&nbsp;{delete}',	    				
	    				'buttons' => array(
	    						'pay' => array(
	    								'url' => 'Yii::app()->controller->createUrl("admin/packagespaymentconfirm/id/".$data->id)',
	    								//. '"packages/paymentconfirm/id/".$data->id)',
	    								//'icon' => 'fa fa-dollar',
	    								//'icon' => 'fa fa-money',
	    								'icon' => 'fa fa-shopping-cart',
	    								'label' => Yii::t('packages', 'Confirm'),
	    								'visible' => 'Package::$_packagePurchaseStatus[\'PURCHASE_STATUS_PENDING\'] == $data->status'
	    						),	    						
	    						'delete' => array(
	    								'url' => 'Yii::app()->controller->createUrl('
	    								. '"admin/packagesremovepurchase/id/".$data->id)',
	    								'icon' => 'fa fa-times',
	    								'label' => Yii::t('packages', 'Cancel order'),
	    								//'visible' => 'Package::$_packagePurchaseStatus[\'PURCHASE_STATUS_PENDING\'] == $data->status'
	    						),
	    				),
	    				'class' => 'bootstrap.widgets.TbButtonColumn',
	    		),
    			
    			   	
    			/*array(
    				'name'=>'package_expire',
    				'value' => '$data->user->package_expire',    				    				
    				'htmlOptions'=> array('style'=>'width:95px;')	            	
    			),*/	
    			
	    )
    ));
    
?>          
