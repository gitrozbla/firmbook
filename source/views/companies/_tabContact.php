<?php
/*
 * Dane firmy w TbTabs
 */
?>
<div class="details">
	<?php if ($company->phone): ?>
    <span class="detail-row">
    	<span class="detail-label"><?php echo Yii::t('company', 'Phone') ?>:</span>
    	<span class="detail-value"><?php
            echo Html::link(
                $company->phone,
                'tel:'.str_replace(array(' ', '-', '&nbsp;'), '', $company->phone)
            );
            for($i=2; $i<=5; $i++) {
                $attribute = 'phone' . $i;
                if ($company->$attribute) {
                    $phones []= Html::link( $company->$attribute,
                        'tel:'.str_replace(array(' ', '-', '&nbsp;'), '', $company->$attribute)
                    );
                }
            }
            if (!empty($phones)) echo ', ' . implode(', ', $phones);
        ?></span>
   	</span>
   	<?php endif; ?>
   	<?php if (!$company->hide_email && $company->email): ?>
   	<span class="detail-row">
    	<span class="detail-label"><?php echo Yii::t('company', 'Email') ?>:</span>
    	<span class="detail-value" style="display: inline-block; vertical-align: top;"><?php
            echo Html::link($company->email, 'mailto:'.$company->email);
            for($i=2; $i<=5; $i++) {
                $attribute = 'email' . $i;
                if ($company->$attribute) {
                    $emails []= Html::link( $company->$attribute,
                        'mailto:' . $company->$attribute
                    );
                }
            }
            if (!empty($emails)) echo '<br />' . implode('<br />', $emails);
        ?></span>
   	</span>
   	<?php endif; ?>
	<?php if ($company->skype): ?>
		<span class="detail-row">
    	<span class="detail-label">Skype</span>
    	<span class="detail-value"><?php echo Html::skypeWidget($company->skype) ?></span>
   	</span>
	<?php endif; ?>
   	<?php if ($item->www): ?>
   	<span class="detail-row">
    	<span class="detail-label"><?php echo Yii::t('company', 'Website') ?>:</span>
	   	<span class="detail-value">
	   		<?php echo Html::link(
		   			str_replace(array('http://', 'https://'), '', $item->www), 
		   			$item->www, 
		   			array('target' => '_blank')                
	   		); ?></span>
	</span>
	<?php endif; ?>			    
</div>