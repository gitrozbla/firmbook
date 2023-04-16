<?php 
/**
 * Formularz - dodanie i edycja filmu.
 *
 * @category views
 * @package accoewsunt
 * @author
 * @copyright (C) 2015
 */ 

	
?>
<?php
    //$class = get_class($product);
    //$source = strtolower($class);    
?>
<div class="row">
	<?php //require './source/views/companies/_companyPanel.php'; ?>
    <div class="span8 well">        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
        	'htmlOptions' => array('class' => 'center'),            
        )); ?>        
        <h1><?php
        	if($movie->getScenario() == 'create')
        		echo Yii::t('movies', 'Add new movie');
        	else 
        		echo Yii::t('movies', 'Edit movie data');     					    
        ?></h1>       
        
        <fieldset>
        <legend><?php echo Yii::t('movies', 'Movie data'); ?>:</legend>        
        <?php echo $form->textFieldRow($movie, 'youtube_link',
            array('style' => 'width:350px'),
        	array('hint' => Yii::t('item', 'Example:')
                . ' http://www.youtube.com/watch?v=videoId')); ?>   
        <?php echo $form->textFieldRow($movie, 'title'); ?>                  
        <?php echo $form->textAreaRow($movie, 'description'); ?>             
        
        		
        
           	
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
        <?php if ($company) : ?>
            <?php $this->renderPartial('/companies/_companyBox', compact('company')); ?>
        <?php endif; ?>       
    </div>
</div>
