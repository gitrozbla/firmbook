<?php 
/**
 * Formularz rejestracyjny.
 *
 * @category views
 * @package account
 * @author
 * @copyright (C) 2015
 */ 

if(!isset($creators)) $creators = false;
?>
<div class="row">
	<div class="span6">
        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
            'htmlOptions' => array('class' => 'well center'),
        )); ?>
        
        <h1><?php
        	if($period->getScenario() == 'insert')
        		echo Yii::t('packages', 'Add period');
        	else 
        		echo Yii::t('packages', 'Edit period'); ?></h1>
        
        <?php 
	        if($period->getScenario() != 'insert')
	        	$options = array('options' => array(Yii::app()->request->getParam('package_id')=>array('selected'=>true)), 'readonly'=>true, "disabled"=>"disabled");
	        else
	        	$options = array('options' => array(Yii::app()->request->getParam('package_id')=>array('selected'=>true)));	 
	        
	        echo $form->dropDownListRow(
				$period,
				'package_id',
				Package::packagesToSelect($creators), $options);
        ?>
				
		<?php 
			if($period->getScenario() != 'insert')
		       	$options = array('readonly'=>true, "disabled"=>"disabled");
		    else
		      	$options = array(); 
			
			echo $form->dropDownListRow(
				$period,
				'period',
				PackagePeriod::periodsToSelect(), $options); 
		?>
		
        <?php echo $form->textFieldRow($period, 'price'); ?>
               
        <hr />
        
		<?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit', 
                'label' => Yii::t('packages', 'Save'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
        		)				
		)); ?>
                
        <?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'link',			      
			    'label' => Yii::t('packages', 'Cancel'),
				'type' => 'primary',
				'url' => $this->createUrl ( 'admin/packagesperiods' ),
		)); ?>    
        <?php $this->endWidget(); ?>
        
    </div>
</div>
