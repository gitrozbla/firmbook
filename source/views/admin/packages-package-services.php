<?php 
/**
 * Admin - usługi w pakiecie.
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */
 
?>
<h1 style=""><i class="fa fa-users"></i> <?php echo Yii::t('packages', 'Services'); ?></h1>
<h2><?php echo Package::badge($package->name, $package->css_name);?></h2>
<br />
<?php 
echo CHtml::beginForm();
$this->widget(
	'ExtendedGridView',    
    array(    		
	    'fixedHeader' => true,
	    'headerOffset' => 0,	    
	    'type' => 'striped',
	    'dataProvider' => PackageService::servicesDataProvider($package->creators),
	    'responsiveTable' => true,
	    'template' => "{items}",
    	'enableSorting' => false,		
    	'extraData'=> $selectedData,
	    'columns' => array(
	    		array(
	    			'header' => '#',
	    			'value' => '$row+1'	
	    		),		 	            
    			/*array(
    				'name'=>'order_index',    				
    				'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),*/
	            'name',    			
    			array(
	    			'name'=>'value_type', 
	    			'value'=>'!$data->value_type ? Yii::t(\'packages\', \'Yes/No\') : Yii::t(\'packages\', \'Numerical\')'
    			),	    		
	    		array(
	    			'id' => 'selectedIds',
	    			'class'=>'CCheckBoxColumn',
	    			'selectableRows'=>2,	    			
	    			'checked'=>'in_array($data->id, $this->grid->extraData[\'id\'])',	    			
	    		),
	    		array(	    			 
                    'header'=>Yii::t('packages', 'Value'),
	    			'value'=>'$data->value_type ? CHtml::textField(\'selectedValue[\'.$data->id.\']\',isset($this->grid->extraData[\'value\'][$data->id]) ? $this->grid->extraData[\'value\'][$data->id] : \'\',array(\'size\'=>5,\'maxlength\'=>4, \'style\'=>"width: 40px;")) : "";',
	    			'type'=>'raw',    			
	    		),
				array(
    				'name'=>'id',    				
    				'htmlOptions'=> array('style'=>'width:20px;')	            	
    			),
	    )
    ));
    $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit', 
                'label' => Yii::t('packages', 'Save'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
                )));
	//echo CHTML::button('Checkout',  array('submit' => Yii::app()->createUrl("order/getInfo")));    
   echo CHtml::endForm(); 
?>

			