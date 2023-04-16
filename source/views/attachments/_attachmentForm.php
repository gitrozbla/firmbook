<?php 
/**
 * Formularza dodania i edycji załącznika
 *
 * @category views
 * @package site
 * @author
 * @copyright (C) 2015
 */ 
?>

        <?php $form = $this->beginWidget('ActiveForm', array(
        	'type' => 'horizontal',
		    'htmlOptions' => array('enctype'=>'multipart/form-data')
		)); ?>
		
			<h1><?php
	        	if($attachment->getScenario() == 'create')
	        		echo Yii::t('attachment', 'Add attachment file');
	        	else 
	        		echo Yii::t('attachment', 'Edit attachment');     					    
	        ?></h1>
	        
	        <?php if($attachment->getScenario() == 'create') : ?>
	        	<?php echo $form->fileFieldRow($attachment, 'file'); ?>
	        <?php endif; ?>
	        
	        <?php echo $form->textFieldRow($attachment, 'anchor'); ?>
	        
	        <?php echo $form->textAreaRow($attachment, 'description') ?>
		
			<div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit', 
                'label' => Yii::t('packages', 'Save'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
                ))); ?>
                
            <?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType' => 'link',			     
			    'label' => Yii::t('packages', 'Cancel'),
			    'type' => 'primary',
            	'url' => $this->createGlobalRouteUrl($controller.'/show', array('name' => $item->alias))
			    //'url' => Yii::app()->request->urlReferrer,
			    )); ?>			            
			</div>	
		
		<?php $this->endWidget();   // form ?>       	
    