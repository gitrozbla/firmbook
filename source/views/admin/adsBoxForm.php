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
        	if($adbox->getScenario() == 'insert')
        		echo 'Dodaj box';
        	else 
        		echo 'Edytuj box'; 
    					    
        ?></h1>
        <?php echo $form->textFieldRow($adbox, 'label'); ?>        
        <?php echo $form->textFieldRow($adbox, 'alias'); ?>
        <?php echo $form->textFieldRow($adbox, 'name'); ?>
        <?php echo $form->textFieldRow($adbox, 'size', null, 
        		array(        			
        			'hint' => 'np. <b>470x67</b>'
        	)); ?>
        <?php echo $form->textFieldRow($adbox, 'height', null, 
        		array(        			
        			'hint' => 'np. <b>240</b>'
        	)); ?>	
        <?php echo $form->textAreaRow($adbox, 'description'); ?>
        <?php echo $form->textFieldRow($adbox, 'period', array(
        		//"value"=>2, 
        		//"disabled"=>"disabled", 
        		"style"=>"width:70px;"), 
        	array(        			
        		'append' => 'tygodnie',
        		'hint' => 'l. naturalna podzielna przez 2, np. 2, 4, 6, 8, 10, 12 ...'
        	)); ?>
        <?php echo $form->textFieldRow($adbox, 'price'); ?>
        <?php echo $form->checkboxRow($adbox, 'carousel'); ?>
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
			                    'url' => $this->createUrl('admin/adsboxes'),
			                	
			                )
			            ); ?>    
        <?php $this->endWidget(); ?>
        
    </div>
</div>
