<?php 
/**
 * Porównanie pakietów.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */ 
?>
<?php
$packagePaid = PackagePurchase::model()->exists('user_id=:user_id and status=:status', array(':user_id'=>Yii::app()->user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID']));
?>
<?php require '_packageDetails.php'; ?>
<div class="well center">            
<h1><?php echo Yii::t('packages', 'Package change history:'); ?></h1>
<?php $this->widget(
    'GridView',
    array(        
    	'responsiveTable' => true,
    	'selectableRows'=>false,
        'type' => 'striped',
        'dataProvider' => User::paymentsDataProvider(Yii::app()->user->id),
    	'template' => '{items}',
    	'enableSorting' => false,
    	'htmlOptions'=> array(
    		 	'style'=>'text-align:center; background-color: #fff;',	
    			),        
        'rowCssClassExpression' => '$data->status == Package::$_packagePurchaseStatus["PURCHASE_STATUS_EXPIRED"] ? "fade-medium" : "";',
   		'afterAjaxUpdate' => 'js: function() {reload();}',
        'columns' => array(    		
    		array('name'=>'name', 'value'=> 'Package::badge($data->package->name, $data->package->css_name)',
    			'type'=>'raw',
    			'htmlOptions'=> array(
    		 		'style'=>'text-align:center; width:130px;',    		 	
    			)
    		),    		
    		'price',
            array('name'=>'status', 'value'=>'Package::statusToLabel($data->status, $data->paid)'),    		
    		array(
    			'name'=>'date_start', 
    			'value'=>'Package::$_packagePurchaseStatus[\'PURCHASE_STATUS_PENDING\'] == $data->status ?  
    					Yii::t(\'packages\', \'After paying\'). 
    					(!$data->force_activation && 0==1/*$packageCurrent[\'id\'] != Package::$_packageDefault*/ ?  
	    				Yii::t(\'packages\', \' and the expiry of the current\'): \'\') : 
	    				Yii::app()->dateFormatter->format("yyyy-MM-dd", $data->date_start)'),
    		array('name'=>'date_expire', 'value'=>'Yii::app()->dateFormatter->format("yyyy-MM-dd", $data->date_expire)'),    		
            array(
            	'template' => '{delete}',
            	//'template' => (Yii::app()->user->isAdmin?'{pay}&nbsp;&nbsp;&nbsp;':'').'{delete}',                
                'buttons' => array(
                	'pay' => array(
                		'url' => 'Yii::app()->controller->createUrl('
                		.'$this->createUrl("packages/paymentconfirm/id/".$data->id)',
                		//. '"packages/paymentconfirm/id/".$data->id)',
                		//'icon' => 'fa fa-dollar',
                		//'icon' => 'fa fa-money',
                		'icon' => 'fa fa-shopping-cart',
                		'label' => Yii::t('packages', 'Confirm'),
                		'visible' => 'Package::$_packagePurchaseStatus[\'PURCHASE_STATUS_PENDING\'] == $data->status'
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
                        'visible' => 'Package::$_packagePurchaseStatus[\'PURCHASE_STATUS_PENDING\'] == $data->status'    
                    ),                    
                ),
				'class' => 'bootstrap.widgets.TbButtonColumn',
            ),    		         
        ),
    )
); ?>
</div>	