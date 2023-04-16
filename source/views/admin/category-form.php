<?php 
/**
 * Formularz nowej kateogrii.
 *
 * @category views
 * @package admin
 * @author
 * @copyright (C) 2015
 */ 
if(isset($category))
	if($category->id) {
		$breadcrumbs = $category->manageCategoryBreadcrumbs();
	} else
		$breadcrumbs = NULL;
else
	$breadcrumbs = $formCategory->manageCategoryBreadcrumbs();
?>
<div class="row">
    <div class="span6">        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
            'htmlOptions' => array('class' => 'well center'),
        )); ?>
        <h1><?php
        	if($formCategory->getScenario() == 'insert')
        		echo Yii::t('categories', 'Add category');
        	else 
        		echo Yii::t('categories', 'Edit category'); 
    					    
        ?></h1>
        <?php $this->widget(
                    'bootstrap.widgets.TbBreadcrumbs',
                    array(
                    	'homeLink' => Html::link('Kategorie', $this->createUrl('admin/categories')),
                        //'homeLink' => Html::link(Yii::t('site', 'Home'), $absoluteHomeUrl),
                        'links' => $breadcrumbs,
                    )
                ); ?>
        <?php echo $form->textFieldRow($formCategory, 'name'); ?>
        <?php echo $form->textFieldRow($formCategory, 'alias'); ?>
        <?php //echo $form->textAreaRow($category, 'description'); ?>        
        <?php //echo $form->checkboxRow($category, 'value_type'); ?>
         <hr />
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit', 
                'label' => Yii::t('common', 'Save'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
                ))); ?>
            <?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('common', 'Cancel'),
			                    'type' => 'primary',
			                    'url' => $this->createGlobalRouteUrl('admin/categories'),
			                	
			                )
			            ); ?>    
        <?php $this->endWidget(); ?>
        
    </div>
</div>
