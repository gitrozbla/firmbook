<?php 
/**
 * Formularz przywracania dostępu.
 *
 * @category views
 * @package account
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<div class="row">
    <div class="offset4 span4">
        <h1><?php echo Yii::t('account', 'Access recovery'); ?></h1>

        <p><?php echo Yii::t('accessRecovery', 'Please type your email or username to recover access.'); ?></p>

        <?php $form = $this->beginWidget('ActiveForm', array(
            'htmlOptions' => array('class' => 'well center'),
        )); ?>
        
            <?php echo $form->textFieldRow($model, 'emailOrUsername', array('class'=>'width-limit')); ?><br />

            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'submit', 
                'label'=>Yii::t('accessRecovery', 'Recover access'), 
                'type'=>'primary')); ?>
            
        <?php $this->endWidget(); ?>

    </div>
</div>