<?php 
/**
 * Formularz - dodanie i edycja danych zdjęcia.
 *
 * @category views
 * @package account
 * @author
 * @copyright (C) 2015
 */ 
	
?>

<div class="row">
	<?php //require './source/views/companies/_companyPanel.php'; ?>
    <div class="span8 well">        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
        	'htmlOptions' => array(
        		'class' => 'center',
        		'enctype'=>'multipart/form-data'
        	),            
        )); ?>        
        <h1><?php
        	if($userfile->getScenario() == 'create')
        		echo 'Dodaj zdjęcie';
        	else 
        		echo 'Edytuj zdjęcie';     					    
        ?></h1>       
        
        <fieldset>
        <legend>Plik:</legend>        
        <?php echo $form->fileFieldRow($userfile, 'data'); ?>  
        <legend>Opis:</legend>           
        <?php //echo $form->textAreaRow($product, 'short_description'); ?>        
                   	
        <hr />
        </fieldset>
 
		<div class="form-actions">
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
			                    'url' => Yii::app()->request->urlReferrer,
			                	
			                )
			            ); ?>    
			            
		</div>	            
        <?php $this->endWidget(); ?>
        
    </div>
    <div class="span3 pull-right">
        <?php /*if ($company) : ?>
            <?php $this->renderPartial('/companies/_companyBox', compact('company')); ?>
        <?php endif;*/ ?>       
    </div>
</div>
