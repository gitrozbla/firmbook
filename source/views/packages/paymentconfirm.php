<?php 
/**
 * Podstrona informująca o poprawnym wylogowaniu.
 *
 * @category views
 * @package account
 * @author
 * @copyright (C) 2014
 */ 
?>
<p class="text-center top-100">
    <i class="fa fa-check"></i> 
    <?php echo Yii::t('packages', 'The operation has been confirmed.'); ?>
    <?php if(Yii::app()->user->isGuest): ?>   
    	<?php echo Html::link(Yii::t('account', 'Go to login page'), $this->createUrl('account/login')); ?>
    <?php else: ?>
    	<?php echo Html::link(Yii::t('account', 'Go to homepage.'), Yii::app()->homeUrl); ?>
    <?php endif; ?>
</p>