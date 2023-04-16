<?php 
/**
 * Formularz artykułu.
 *
 * @category views
 * @package admin
 * @author
 * @copyright (C) 2015
 */ 
?>
<div class="row">
    <div class="span9">
        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
            'htmlOptions' => array('class' => 'well center'),
        )); ?>
        <h1><?php
        	if($article->getScenario() == 'insert')
        		echo Yii::t('articles', 'Add article');
        	else 
        		echo Yii::t('articles', 'Edit article'); 
    					    
        ?></h1>
        <?php echo $form->textFieldRow($article, 'label'); ?>        
        <?php echo $form->textFieldRow($article, 'alias'); ?>
        <?php echo $form->textFieldRow($article, 'title'); ?>
        <?php echo $form->ckEditorRow($article, 'content'); ?>
        <?php //echo $form->textAreaRow($article, 'content'); ?>
        <?php echo $form->checkBoxRow($article, 'visible'); ?>        
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
			                    'label' => Yii::t('common', 'Close'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('admin/articles'),
			                	
			                )
			            ); ?>    
        <?php $this->endWidget(); ?>
        
    </div>
</div>
