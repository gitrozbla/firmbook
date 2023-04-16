<?php
/*
 * Przesyłki
 */
?>
<?php if ($company->free_delivery) {
	echo '<div class="alert alert-success free-delivery">'
		. '<i class="fa fa-check"></i> ' . Yii::t('product', 'Free delivery')
	. '</div>';
} ?>

<?php if($company->delivery_type && is_array($company->delivery_type)) : ?>
	<div class="company-delivery">
		<?php foreach ($company->delivery_type as $value) :?>
		<?php
			if (isset(Yii::app()->params->delivery[$value])) {
				$value = Yii::app()->params->delivery[$value];
				if (isset($value['image'])) {
					$url = 'images/delivery_icons/'.$value['image'];
					if (Yii::app()->params['websiteMode'] == 'creators' && $this->id == 'generator') {
						$url = $this->mapFile($url, 'delivery-methods');
					}
					$delivery = CHtml::image($url, $value['name']);
				} else {
					$delivery = '<div class="delivery-text">' . Yii::t('product', $value['name']) . '</div>';
				}
				if (isset($value['url'])) {
					echo Html::link($delivery, $value['url'][Yii::app()->language], array('target'=>'_blank')).' ';
				} else {
					echo $delivery;
				}
			}
		?>
		<?php endforeach;?>
	</div>

	<br/><br/>
<?php endif; ?>

<?php /*
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Html::link(Yii::t('company', 'Comparison courier services'), 'http://shiplo.com/pl/') ?>
&nbsp;&nbsp;<i class="fa fa-check"></i><br />
*/?>
<?php $this->widget(
  'Button',
  array(
  	'buttonType' => 'link',
    'label' => Yii::t('company', 'Comparison courier services'),
    //'type' => 'success',
    'url'=> 'http://www.znajdzkuriera.pl/',
  	'icon' => 'fa fa-bar-chart-o',
    'htmlOptions' => array(
  		'style'=>'margin-left: 10px;',
    	'target'=>'_blank'
  		//'title' => Yii::t('company', 'Comparison courier services'),
  	),
  )
); ?>
