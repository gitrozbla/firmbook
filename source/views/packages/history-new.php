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
if (PackagePurchase::model()->exists('user_id=:user_id and status=:status', array(':user_id'=>Yii::app()->user->id, ':status'=>Package::$_packagePurchaseStatus['PURCHASE_STATUS_PAID'])))
	$packagePending = true;
else	
	$packagePending = false;
?>
<?php if (Yii::app()->user->isGuest == false) {
                require 'packageDetails.php';
            } ?>
<div class="well center">            
<h1><?php echo Yii::t('packages', 'Package change history:'); ?></h1>
<?php $this->widget(
    'bootstrap.widgets.TbExtendedGridView',
    array(        
    	'fixedHeader' => true,
	    'headerOffset' => 0,
        'type' => 'striped',
        'dataProvider' => User::paymentsDataProvider(Yii::app()->user->id),
    	'template' => '{items}',
    	'enableSorting' => false,
    	'selectableRows'=>1,
    	'responsiveTable' => true,
    	'htmlOptions'=> array(
    		 	'style'=>'text-align:center; background-color: #fff;',	
    			),        
        //'rowCssClassExpression' => '$data->status == Package::$_packagePurchaseStatus["PURCHASE_STATUS_EXPIRED"] ? "fade-medium" : "";',
        'columns' => array(    		
    		array('name'=>'name', 'value'=> '$data->package->name',
    		 
    		),    		
    		'price',
                		
    		    		
            array(
            	'template' => '{login}&nbsp;&nbsp;&nbsp;{delete}',                
                'buttons' => array(
            		'login' => array(
                        'url' => 'Yii::app()->controller->createUrl('
                            . '"packages/paymentconfirm/id/".$data->package_id)',                       
                        //'icon' => 'fa fa-dollar',
                        //'icon' => 'fa fa-money',                       
                        'icon' => 'fa fa-shopping-cart',                        
                        'label' => Yii::t('packages', 'Pay'),
                        //'visible' => 'Package::$_packagePurchaseStatus[\'PURCHASE_STATUS_PENDING\'] == $data->status'                        
                    ),
                    'delete' => array(
                    	'url' => 'Yii::app()->controller->createUrl('
                            . '"packages/cancel_order/id/".$data->id)',
                        //'icon' => 'fa fa-times',                        
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