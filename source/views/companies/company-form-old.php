<?php 
/**
 * Formularz - dodanie i edycja danych firmy.
 *
 * @category views
 * @package account
 * @author
 * @copyright (C) 2015
 */ 
?>
<div class="row">
    <div class="span8">
        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
            'htmlOptions' => array('class' => 'well center'),
        )); ?>
        <h1><?php
        	if($company->getScenario() == 'create')
        		echo Yii::t('company', 'Add new company');
        	else 
        		echo Yii::t('company', 'Edit company data'); 
    					    
        ?></h1>
        <hr />
        <?php $this->widget(
			'bootstrap.widgets.TbSelect2',
			array(
				
				'name' => 'category1',
				'data' => null,
				'options' => array(				
					'placeholder' => 'type clever, or is, or just type!',
					'width' => '40%',					
				)
			));			
        ?>
        <hr />
        <?php /*$this->widget(
			'bootstrap.widgets.TbSelect2',
			array(
				'asDropDownList' => false,
				//'label' => Yii::t('packages', 'Save'),
				'name' => 'category_id',
				'options' => array(
				'tags' => array('clever', 'is', 'better', 'clevertech'),
				'placeholder' => 'type clever, or is, or just type!',
				'width' => '40%',
				'tokenSeparators' => array(',', ' ')
			)));*/			
        ?>
        <hr />
        <?php 
        //$data = '[{"id":"413","text":"Akcesoria samochodowe"},{"id":"444","text":"Konserwacja samochodu"},{"id":"451","text":"Cz\u0119\u015bci samochodowe"},{"id":"410","text":"Samochody"},{"id":"521","text":"Motocykle"},{"id":"559","text":"Powi\u0105zane produkty i us\u0142ugi"},{"id":"559","text":"Powi\u0105zane produkty i us\u0142ugi"},{"id":"559","text":"Powi\u0105zane us\u0142ugi i produkty"},{"id":"540","text":"Sprz\u0119t i narz\u0119dzia motoryzacyjne"}]';
        //$tags=array('Satu','Dua','Tiga');
//echo CHtml::textField('test','',array('id'=>'test'));
$this->widget('bootstrap.widgets.TbSelect2',array(
  	'data' => array('RU' => 'Russian Federation', 'CA' => 'Canada', 'US' => 'United States of America', 'GB' => 'Great Britain'),
	'name' => 'category2',
	'model' => $item,
	'options'=>array(		
	    //'tags'=>$tags,
	    
	),
));
echo '<br/><br/><br/>';   
//echo CHtml::textField('test','',array('id'=>'test'));  
 /*echo CHtml::activeTextField($item,
'category_id',
array(
'data-init-text' => 'ble'
)
);
$id = "Item_category_id";*/
   
$this->widget('bootstrap.widgets.TbSelect2',array(
	//'selector' => "#" . $id,
	'asDropDownList' => false,
  	//'selector'=>'#test',
  	//'data' => array('RU' => 'Russian Federation', 'CA' => 'Canada', 'US' => 'United States of America', 'GB' => 'Great Britain'),
	'name' => 'category_id',
	'model' => $item,
  	'options'=>array(	
    	'ajax' => array(
        	'url' => Yii::app()->createUrl('categories/json_get_subcategories'),
			//'dataType' => 'jsonp',
        			/*'data' => 'js: function(text,page) {
                                        return {
                                            q: text,
                                           
                                        };
                                    }',*/
        ),
  	),
));
echo '<br/><br/><br/>';
        ?>
        <?php 
        $opt = array(
  			//'data' => array('RU' => 'Russian Federation', 'CA' => 'Canada', 'US' => 'United States of America', 'GB' => 'Great Britain'),
			//'name' => 'category2',
			//'model' => $item,
  			'options'=>array(
        		/*'ajax' => array(
                	'url' => Yii::app()->createUrl('categories/json_get_subcategories'),
        			'data' => 'js: function(text,page) {
                                        return {
                                            q: text,
                                           
                                        };
                                    }',
        		),*/
        		
        	),
		);
        //echo $form->select2Row($item, 'category_id', $opt); 
        ?>
        <?php echo $form->textFieldRow($item, 'name'); ?>
        <?php echo $form->textFieldRow($item, 'alias'); ?>                 
        <?php echo $form->textAreaRow($company, 'short_description'); ?>
        <?php //echo $form->textAreaRow($company, 'description'); ?>
        <?php echo $form->textFieldRow($company, 'phone'); ?>       
        <?php echo $form->textFieldRow($company, 'email'); ?>
        <?php echo $form->ckEditorRow($item, 'description'); ?>
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
			                    'url' => Yii::app()->request->urlReferrer,
			                	
			                )
			            ); ?>    
        <?php $this->endWidget(); ?>
        
    </div>
</div>
