<?php
/*
 * Dane firmy w TbTabs
 */
?>
<div class="details">
    <span class="detail-row">
        <span class="detail-label"><?php echo Yii::t('company', 'Name') ?>:</span>
        <span class="detail-value"><?php echo $item->name; ?></span>
    </span>
    <?php if ($company->origin): ?>
    <?php $company->origin->name = Yii::t('country.name', $company->origin->name, null, 'dbMessages') ?>

    <?php /*if (!empty($company->legal_form)) : ?>
            <span class="detail-row">
                    <span class="detail-label"><?php echo Yii::t('company', 'Legal form'); ?>:</span>
                    <span class="detail-value">
                                    <?php echo Yii::t('company', Company::$legalForms[$company->legal_form]); ?>
                            </span>
            </span>
    <?php endif;*/ ?>

    <span class="detail-row">
            <span class="detail-label"><?php echo Yii::t('company', 'Country') ?>:</span>
            <span class="detail-value">
        <?php
            $flagImage = 'images/flag_icons/'.strtolower($company->country).'.gif';
            if (Yii::app()->params['websiteMode'] == 'creators' && $this->id == 'generator') {
                    $flagImage = $this->mapFile($flagImage);
            }
            echo CHtml::image($flagImage,
            '',
            array(
                    'title' => $company->origin->name,
            )
            /*, $company->origin->name*/) ?>
        <?php $this->widget(
                'application.components.widgets.TextOverflowScroll',
                array(
                    'text' => $company->origin->name,
                )
        ); ?>
    </span>
</span>
    <?php endif; ?>
    <?php if ($company->nip): ?>
	<span class="detail-row">
            <span class="detail-label"><?php echo Yii::t('company', 'NIP') ?>:</span>
            <span class="detail-value"><?php echo $company->nip; ?>
            <?php if ($company->allow_verification): ?>
                &nbsp;&nbsp;
                <?php if ($company->isUeNip()): ?>
                    <?php echo Html::link(Yii::t('company', 'Check registers'),
                        'http://ec.europa.eu/taxation_customs/vies/vatRequest.html',
                        array('target'=>'_blank', 'rel' => 'nofollow')); ?>
                <?php else: ?>
                    <?php echo Html::link(Yii::t('company', 'Check registers'),
                        'https://wyszukiwarkaregon.stat.gov.pl/appBIR/index.aspx',
                        array('target'=>'_blank', 'rel' => 'nofollow')); ?>
                <?php endif; ?>
                &nbsp;&nbsp;<i class="fa fa-check"></i><br />
        <?php endif; ?>
	   	</span>
	</span>
	<?php endif; ?>
	<?php /*if ($company->nip): ?>
	<span class="detail-row">
	   	<span class="detail-label"><?php echo Yii::t('company', 'NIP') ?>:</span>
	   	<span class="detail-value"><?php echo $company->nip; ?>
	   	<?php if ($company->allow_verification): ?>
             &nbsp;&nbsp;
            <?php echo Html::link(Yii::t('company', 'Check registers')
							. '&nbsp;&nbsp;<img src="images/ceidg_24.png" alt="CEIDG" />',
								'https://prod.ceidg.gov.pl/CEIDG/CEIDG.Public.UI/Search.aspx',
								array('target'=>'_blank', 'rel' => 'nofollow')); ?>
						&nbsp;&nbsp;<i class="fa fa-check"></i><br />
        <?php endif; ?>
	   	</span>
	</span>
	<?php endif;*/ ?>
	<?php if ($company->regon): ?>
	<span class="detail-row">
	   	<span class="detail-label"><?php echo Yii::t('company', 'REGON') ?>:</span>
	   	<span class="detail-value"><?php echo $company->regon; ?>
	   	<?php if ($company->allow_verification): ?>
                    &nbsp;&nbsp;                    
                    <?php echo Html::link(Yii::t('company', 'Check registers'),
                        'https://wyszukiwarkaregon.stat.gov.pl/appBIR/index.aspx',
                        array('target'=>'_blank', 'rel' => 'nofollow'));
                    ?>
                    <?php /*if ($company->legal_form == 2) {
                            echo Html::link(Yii::t('company', 'Check registers')
                                    . '&nbsp;&nbsp;<img src="images/ekrs_32.png" alt="EKRS" />',
                                            'https://ems.ms.gov.pl/krs/wyszukiwaniepodmiotu',
                                            array('target'=>'_blank', 'rel' => 'nofollow'));
                    } else {
                            echo Html::link(Yii::t('company', 'Check registers')
                                    . '&nbsp;&nbsp;<img src="images/ceidg_24.png" alt="CEIDG" />',
                                            'https://prod.ceidg.gov.pl/CEIDG/CEIDG.Public.UI/Search.aspx',
                                            array('target'=>'_blank', 'rel' => 'nofollow'));
                    };*/ ?>
                    &nbsp;&nbsp;<i class="fa fa-check"></i><br />
                <?php endif; ?>
	    </span>
	</span>
	<?php endif; ?>
	<?php if ($company->krs): ?>
	<span class="detail-row">
	   	<span class="detail-label"><?php echo Yii::t('company', 'KRS number') ?>:</span>
	   	<span class="detail-value"><?php echo $company->krs; ?>
	   	<?php if ($company->allow_verification): ?>
                    &AElig;&nbsp;&nbsp;
                    <?php echo Html::link(Yii::t('company', 'Check registers'),
                        'https://wyszukiwarkaregon.stat.gov.pl/appBIR/index.aspx',
                        array('target'=>'_blank', 'rel' => 'nofollow'));
                    ?>
						<?php /*echo Html::link(Yii::t('company', 'Check registers')
							. '&nbsp;&nbsp;<img src="images/ekrs_32.png" alt="EKRS" />',
								'https://ems.ms.gov.pl/krs/wyszukiwaniepodmiotu',
								array('target'=>'_blank', 'rel' => 'nofollow'));*/ ?>
                    &nbsp;&nbsp;<i class="fa fa-check"></i><br />
        <?php endif; ?>
	   	</span>
	</span>
	<?php endif; ?>
</div>
