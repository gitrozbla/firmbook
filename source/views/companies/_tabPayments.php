<?php
/*
 * Metody płatności
 */
?>
<?php if($company->payment_type && is_array($company->payment_type)) : ?>
	<div class="company-payments">
		<?php foreach ($company->payment_type as $value) :?>
		<?php
			if (isset(Yii::app()->params->payments[$value])) {
				$value = Yii::app()->params->payments[$value];
				if (isset($value['image'])) {
					$url = 'images/payment_icons/'.$value['image'];
					if (Yii::app()->params['websiteMode'] == 'creators' && $this->id == 'generator') {
						$url = $this->mapFile($url, 'payment-methods');
					}
					$payment = CHtml::image($url, $value['name']);
				} else {
					$payment = '<div class="payment-text">' . Yii::t('company', $value['name']) . '</div>';
				}
				if (isset($value['url'])) {
					echo Html::link($payment, $value['url'][Yii::app()->language], array('target'=>'_blank', 'rel' => 'nofollow')).' ';
				} else {
					echo $payment;
				}
			}
		?>
		<?php //echo CHtml::image('images/payment_icons/'.Yii::app()->params->payments[$value].'.png', Yii::app()->params->payments[$value], array('style'=>'margin-left: 10px;')); ?>
		<?php endforeach;?>
	</div>
<?php endif; ?>


<?php if ($company->payment_wire_transfer) : ?>
	<hr />
	<h4><?php echo Yii::t('company', 'Wire transfer'); ?></h4>

	<?php if ($company->payment_bank_account) : ?>
		<div class="details">
			<span class="detail-row">
			   	<span class="detail-label">
						<?php echo Yii::t('company', 'Bank account number'); ?>:
					</span>
			   	<span class="detail-value">
						<?php echo $company->payment_bank_account; ?>
					</span>
			</span>
			<?php if ($company->payment_swift_code) : ?>
				<span class="detail-row">
				   	<span class="detail-label">
							<?php echo Yii::t('company', 'SWIFT/BIC code'); ?>:
						</span>
				   	<span class="detail-value">
							<?php echo $company->payment_swift_code; ?>
						</span>
				</span>
			<?php endif; ?>
		</div>
	<?php endif; ?>
<?php endif; ?>


<?php if ($company->payment_cash) : ?>
	<hr />
	<h4>
		<i class="fa fa-check"></i>
		<?php echo Yii::t('company', 'Pay with cash'); ?>
	</h4>
<?php endif; ?>
