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
<h1><i class="fa fa-building-o"></i> Zamówienia</h1>
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
	    'dataProvider' => $order->adminSearch(),
    	'filter' => $order,
	    'responsiveTable' => true,
	    //'template' => "{items}",
    	'enableSorting' => true,
    	'rowCssClassExpression' => '$data->status == Package::$_packagePurchaseStatus["PURCHASE_STATUS_EXPIRED"] ? "fade-medium" : "";',
	    'columns' => array(	 
	    	array(
	    		'name'=>'id',
	    		//'name'=>'box.label',
	    		//'value' => '$data->box->label',
	    		'htmlOptions'=> array(
	    			'style'=>'text-align:center; width:50px;',
	    		)
	    	),
    		array(
    			'name'=>'box_label',
    			//'name'=>'box.label',
    			'value' => '$data->box->label',
    			'htmlOptions'=> array(
    		 		'style'=>'text-align:center; width:50px;',    		 	
    			)
    		), 
        	'box.name',
        	array(
        		'name'=>'price',
        		'htmlOptions'=> array(
        			'style'=>'text-align:center; width:80px;',
        		)
        	),
        	array(
        		'name'=>'period',
        		'value'=>'$data->period',
        		'htmlOptions'=> array(
        			'style'=>'text-align:center; width:80px;',
        		)
        	),        	
        	array(
        		'name'=>'date',
        		'value'=>'Yii::app()->dateFormatter->format("yyyy-MM-dd", $data->date)',
        		'htmlOptions'=> array(
        			'style'=>'text-align:center; width:80px;',
        		)
        	),
        	array(
        		'name'=>'paid',
        		'value'=>'$data->paid ? 
        					($data->t_status== Yii::app()->params[\'packages\'][\'dotpayStatus\'][\'completed\'] ? Yii::t(\'ad\',\'Yes\') : Yii::t(\'packages\', \'Unconfirmed\'))
        					: Yii::t(\'ad\', \'No\')',
        		'htmlOptions'=> array(
        			'style'=>'text-align:center; width:120px;',
        		)
        	),
	    	array(
	    		'name'=>'user_username',
	    		'value' => 'isset($data->user) ? $data->user->username : ""',
	    		'htmlOptions'=> array('style'=>'width:150px;')
	    	),
	    		/*array(
	    			'name'=>'user_username',
	    			'value' => '$data->user->username',
	    			//'htmlOptions'=> array('style'=>'width:120px;')
	    		),*/
    			
	    		/*array(
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
	    		),*/
    			
    			   	
    			/*array(
    				'name'=>'package_expire',
    				'value' => '$data->user->package_expire',    				    				
    				'htmlOptions'=> array('style'=>'width:95px;')	            	
    			),*/	
    			
	    )
    ));
    
?>          
