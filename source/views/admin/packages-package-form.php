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
        	if($package->getScenario() == 'insert')
        		echo Yii::t('packages', 'Add package');
        	else 
        		echo Yii::t('packages', 'Edit package'); 
    					    
        ?></h1>
        <?php echo $form->textFieldRow($package, 'name'); ?>        
        <?php echo $form->textAreaRow($package, 'description'); ?>
        <?php echo $form->textFieldRow($package, 'css_name'); ?>  
        <?php echo $form->textAreaRow($package, 'badge_css'); ?>      
        <?php echo $form->textAreaRow($package, 'item_css'); ?>
        <?php echo $form->textFieldRow($package, 'color'); ?>
        <?php echo $form->textFieldRow($package, 'stats_color'); ?>
        <?php echo $form->textFieldRow($package, 'test_period'); ?>
        <?php //echo $form->checkBoxRow($package, 'creators'); ?>    
        <?php echo $form->checkboxRow($package, 'active'); ?>
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
			                    'url' => $this->createUrl('admin/packages'),
			                	
			                )
			            ); ?>    
        <?php $this->endWidget(); ?>
        
    </div>
</div>
