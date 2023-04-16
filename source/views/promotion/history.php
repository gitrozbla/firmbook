<?php 
/**
 * Lista zamówień użytkownika
 *
 * @category views
 * @package promotion
 * @author
 * @copyright (C) 2014
 */ 
?>
<div class="well center">            
<h1><?php echo Yii::t('ad', 'Orders for advertising:'); ?></h1>
<?php $this->widget(
    'GridView',
    array(        
    	'responsiveTable' => true,
    	'selectableRows'=>false,
        'type' => 'striped',
        'dataProvider' => AdOrder::ordersDataProvider(Yii::app()->user->id),
    	'template' => '{items}',
    	'enableSorting' => false,
    	'htmlOptions'=> array(
    		 	'style'=>'text-align:center; background-color: #fff;',	
    			),        
        //'rowCssClassExpression' => '$data->status == Package::$_packagePurchaseStatus["PURCHASE_STATUS_EXPIRED"] ? "fade-medium" : "";',
   		'afterAjaxUpdate' => 'js: function() {reload();}',
        'columns' => array(    		
    		array(
    			'name'=>'box.label',    			
    			'htmlOptions'=> array(
    		 		'style'=>'text-align:center; width:80px;',    		 	
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
        			'template' => '{pay}',
        				//'template' => '{pay}&nbsp;&nbsp;&nbsp;{delete}',
        				//'template' => (Yii::app()->user->isAdmin?'{pay}&nbsp;&nbsp;&nbsp;':'').'{delete}',
        				'buttons' => array(
        						'pay' => array(
        								'url' => 'Yii::app()->controller->createUrl("promotion/pay/id/".$data->id)',
        								//. '"packages/paymentconfirm/id/".$data->id)',
        								//'icon' => 'fa fa-dollar',
        								//'icon' => 'fa fa-money',
        								'icon' => 'fa fa-shopping-cart',
        								'label' => Yii::t('ad', 'Pay'),
        								'visible' => '!$data->paid'
        						),
        						/*'pay' => array(
        						 'url' => 'Yii::app()->controller->createUrl('
        						 		.'$this->createUrl("packages/paymentconfirm/id/".$data->id)',
        						 		//. '"packages/paymentconfirm/id/".$data->id)',
        								//'icon' => 'fa fa-dollar',
        								//'icon' => 'fa fa-money',
        								'icon' => 'fa fa-shopping-cart',
        								'label' => Yii::t('packages', 'Pay'),
        								'visible' => 'Package::$_packagePurchaseStatus[\'PURCHASE_STATUS_PENDING\'] == $data->status'
        						),*/
        						'delete' => array(
        								'url' => 'Yii::app()->controller->createUrl('
        								. '"packages/cancel_order/id/".$data->id)',
        								'icon' => 'fa fa-times',
        								'label' => Yii::t('packages', 'Cancel order'),
        								//'visible' => 'Package::$_packagePurchaseStatus[\'PURCHASE_STATUS_PENDING\'] == $data->status'
        						),
        				),
        				'class' => 'bootstrap.widgets.TbButtonColumn',
        		),
        ),
    )
); ?>
</div>	