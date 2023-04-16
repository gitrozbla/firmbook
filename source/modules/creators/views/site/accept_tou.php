<?php if (!empty($tou->title)) : ?>
    <h1><?php echo Yii::t('article.title', $tou->title, array(), 'dbMessages'); ?></h1>
<?php endif; ?>
    
<div class="terms-of-use">
    <?php if (!empty($tou->content)) : ?>
        <?php echo Yii::t('article.content', '{'.$tou->alias.'}', array('{'.$tou->alias.'}' => $tou->content), 'dbMessages'); ?>
    <?php else : ?>
        <?php echo Yii::t('pages', 'Content will be filled soon...'); ?>
    <?php endif; ?>
</div>

<?php $form = $this->beginWidget('ActiveForm'); ?>
    <div class="form-actions text-right">
        <?php echo $form->checkBoxRow($user, 'creators_tou_accepted'); ?>

        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit', 
            'label'=>Yii::t('account', 'Continue'), 
            'type'=>'primary')); ?>
    </div>
<?php $this->endWidget(); ?>