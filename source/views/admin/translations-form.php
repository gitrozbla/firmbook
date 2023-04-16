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
    <div class="span9">
<?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
            'htmlOptions' => array('class' => 'well center'),
        )); ?>        
<h1><?php if($translation->getScenario() == 'insert')
	echo 'Dodaj tłumaczenie '.$modelParams['formTitle'];
	//echo Yii::t('articles', 'Add translation');
else
	echo 'Edytuj tłumaczenie '.$modelParams['formTitle'];
	//echo Yii::t('articles', 'Edit translation'); ?></h1>

<b>Tytuł:</b> <?php echo $modelParams['itemTitle'];?>
<br /><b>Treść:</b> <?php echo $modelParams['itemContent'];?>
<hr />	
<?php 
	if($translation->getScenario() != 'insert')
       	$options = array('readonly'=>true, "disabled"=>"disabled");
    else
       	$options = array(); 
	echo $form->dropDownListRow(
		$translation,
		'language',
		Yii::app()->params->languages, $options
	); 
?>	
<?php echo $form->textFieldRow($translation, 'title'); ?>

<?php if($modelParams['model'] == 'Package') :?>    
	<?php echo $form->textAreaRow($translation, 'content'); ?>
<?php elseif($modelParams['model'] == 'Category' 
		|| $modelParams['model'] == 'Ad' 
		|| $modelParams['model'] == 'AdsBox'): ?>
	<?php echo $form->textFieldRow($translation, 'content'); ?>
<?php elseif($modelParams['model'] == 'PackageService'): ?>
	<?php echo $form->textAreaRow($translation, 'content'); ?>
<?php else: ?>
	<?php echo $form->ckEditorRow($translation, 'content'); ?>
<?php endif; ?>    
<?php if (!empty($aliasWarning)) :?>
    <div class="text-error"><?php echo $aliasWarning; ?></div>
<?php endif; ?>
<?php //echo $form->textAreaRow($translation, 'content'); ?>

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
			                    'url' => $this->createUrl($modelParams['url']),
			                	
			                )
			            ); ?>
<?php $this->endWidget(); ?>     
    </div>
</div>
