<?php 
/**
 * Oferta banerowa.
 *
 * @category views
 * @package promotion
 * @author
 * @copyright (C) 2014
 */ 

$selectedId = Yii::app()->request->getParam('id');
$boxes = AdsBox::model()->findAll();

$js_periods = 'var boxes=[]; ';
foreach($boxes as $box) {
	$js_periods .= 'boxes["'.$box['id'].'"]='.$box['price'].'; ';
}

?>

<div class="row">
    <div class="span6">        
        <?php $form = $this->beginWidget('ActiveForm', array(            
            'htmlOptions' => array('class' => 'well center'),
        )); ?>        
            <h1><?php echo Yii::t('ad', 'Advertise on firmbook'); ?></h1>
            <?php echo Yii::t('packages', 'Configure your order.'); ?>            
            <hr />
			<?php echo $form->dropDownListRow(
				$order,
				'box_id',
				CHtml::listData($boxes,'id','label'),
				//AdsBox::boxesToSelect(),
				array('options' => array($selectedId=>array('selected'=>true)))
			); ?>
			<?php echo $form->dropDownListRow(
				$order,
				'period',
				array_combine(AdOrder::$_periods, AdOrder::$_periods)); ?>
			<?php /*echo $form->dropDownListRow(
				$purchase,
				'period',
				PackagePeriod::periodsToSelect()
			);*/ ?>
			<?php echo $form->textFieldRow($order, 'price', 
					array("disabled"=>"disabled", "style"=>"width:70px;"),
					array(
							'append' => 'PLN',
							
					)
			); ?>
			<?php 
				//echo $form->checkBoxRow($purchase, 'force_activation'); 
			?>
            <hr />
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit', 
                'label' => Yii::t('packages', 'Buy now'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
                ))); ?> 
                     
        <?php $this->endWidget(); ?>
         
    </div>    
    <div class="span6"> 
    	<?php $this->renderPartial('_adboxList') ?>    	    
		<?php /*   
        <br />
        <h2><?php echo Yii::t('article.title', $articleRight1->title, array(), 'dbMessages'); ?></h2>
        <?php echo Yii::t('article.content', '{'.$articleRight1->alias.'}', array('{'.$articleRight1->alias.'}'=>$articleRight1->content), 'dbMessages'); ?>
        <h2><?php echo Yii::t('article.title', $articleRight2->title, array(), 'dbMessages'); ?></h2>
        <?php echo Yii::t('article.content', '{'.$articleRight2->alias.'}', array('{'.$articleRight2->alias.'}'=>$articleRight2->content), 'dbMessages'); ?>
        */ ?>
    </div>
</div>
<?php 
 
	$str_js = $js_periods.
	" 
	$('#AdOrder_price').val(boxes[$('#AdOrder_box_id').val()]); 
 	$('#AdOrder_box_id, #AdOrder_period').change(function () {	
 		$('#AdOrder_price').val(boxes[$('#AdOrder_box_id').val()]*$('#AdOrder_period').val()/2);
 	});	
	";
	     
    Yii::app()->clientScript->registerScript('box-change', $str_js);

?>

