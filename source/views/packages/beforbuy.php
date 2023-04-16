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
            'htmlOptions' => array('class' => 'well center'),
        )); ?>        
            <h1><?php echo Yii::t('packages', 'Upgrade your account'); ?> <?php echo Yii::app()->name; ?></h1>            
            <?php echo Yii::t('packages', 'Configure your order.'); ?>            
            <hr />
			<?php echo $form->dropDownListRow(
				$purchase,
				'package_id',
				Package::packagesToSelect(),
				array('options' => array(Yii::app()->request->getParam('package_id')=>array('selected'=>true)))
			); ?>
			<?php echo $form->dropDownListRow(
				$purchase,
				'period',
				PackagePeriod::periodsToSelect()
			); ?>
			<?php 
				if(Yii::app()->user->package_id != Package::$_packageDefault)
					echo $form->checkBoxRow($purchase, 'force_activation'); 
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
        <br />
        <h2><?php echo Yii::t('article.title', $articleRight1->title, array(), 'dbMessages'); ?></h2>
        <?php echo Yii::t('article.content', '{'.$articleRight1->alias.'}', array('{'.$articleRight1->alias.'}'=>$articleRight1->content), 'dbMessages'); ?>
        <h2><?php echo Yii::t('article.title', $articleRight2->title, array(), 'dbMessages'); ?></h2>
        <?php echo Yii::t('article.content', '{'.$articleRight2->alias.'}', array('{'.$articleRight2->alias.'}'=>$articleRight2->content), 'dbMessages'); ?>
    </div>
</div>
