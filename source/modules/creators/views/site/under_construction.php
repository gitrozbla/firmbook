<?php 
/**
 * Komunikat o błędzie.
 *
 * @category views
 * @package main
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>

<div class="text-center">
    <h1>
        <i class="fa fa-exclamation-triangle"></i> 
        <?php echo Yii::t('CreatorsModule.site', 'Under Construction'); ?>
    </h1>
    <p>
        <?php echo Yii::t('CreatorsModule.site', 'Please come back later.'); ?><br />
        <?php echo Yii::t('error', 'Go to') . ' ' . CHtml::link(Yii::t('error', 'homepage'), Yii::app()->homeUrl) . '.'; ?>
    </p>
</div>
