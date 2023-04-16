<?php 
/**
 * Podstrona informująca o poprawnym wylogowaniu.
 *
 * @category views
 * @package account
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<p class="text-center top-100">
    <i class="fa fa-check"></i> 
    <?php echo Yii::t('account', 'User has been logged out.'); ?> 
    <?php echo Html::link(Yii::t('account', 'Go to homepage.'), Yii::app()->homeUrl); ?>
</p>