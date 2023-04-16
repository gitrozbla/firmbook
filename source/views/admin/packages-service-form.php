<?php 
/**
 * Formularz rejestracyjny.
 *
 * @category views
 * @package account
 * @author
 * @copyright (C) 2015
 */ 


?>
<div class="row">
    <div class="span6">        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
            'htmlOptions' => array('class' => 'well center'),
        )); ?>
        <h1><?php
        	if($service->getScenario() == 'insert')
        		echo Yii::t('packages', 'Add service');
        	else 
        		echo Yii::t('packages', 'Edit service'); 
    					    
        ?></h1>
        <?php echo $form->textFieldRow($service, 'name'); ?>
        <?php echo $form->textAreaRow($service, 'description'); ?>
        <?php echo $form->textAreaRow($service, 'instruction'); ?>
        <?php echo $form->textFieldRow($service, 'role'); ?>
        <?php echo $form->checkboxRow($service, 'value_type'); ?>
        <?php echo $form->checkboxRow($service, 'active'); ?>
        <?php //echo $form->checkBoxRow($service, 'creators'); ?>
         <hr />
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit', 
                'label' => Yii::t('packages', 'Save'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
                ))); ?>
            <?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Cancel'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('admin/packagesservices'),
			                	
			                )
			            ); ?>    
        <?php $this->endWidget(); ?>
        
    </div>
</div>
