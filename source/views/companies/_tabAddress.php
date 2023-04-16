<?php
/*
 * Dane firmy w TbTabs
 */
?>
<div class="details">
	<?php if ($company->province): ?>
	<span class="detail-row">
    	<span class="detail-label"><?php echo Yii::t('company', 'Province') ?>:</span>
    	<span class="detail-value"><?php echo $company->province; ?></span>
    </span>
    <?php endif; ?>
    <?php if ($company->city): ?>
	<span class="detail-row">
    	<span class="detail-label"><?php echo Yii::t('company', 'City') ?>:</span>
    	<span class="detail-value"><?php echo $company->city; ?></span>
    </span>
    <?php endif; ?>
    <?php if ($company->street): ?>
    <span class="detail-row">
    	<span class="detail-label"><?php echo Yii::t('company', 'Street') ?>:</span>
    	<span class="detail-value"><?php echo $company->street; ?></span>
    </span>    
    <?php endif; ?>
    <?php if ($company->postcode): ?>
    <span class="detail-row">
    	<span class="detail-label"><?php echo Yii::t('company', 'Postcode') ?>:</span>
    	<span class="detail-value"><?php echo $company->postcode; ?></span>
   </span>
   <?php endif; ?>
</div>