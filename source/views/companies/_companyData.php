<?php
	// THIS FILE IS USED BOTH IN FIRMBOOK AND CREATORS!
	$viewPrefix = '//companies/';
?>

<?php if(isset($company->map_lat) && isset($company->map_lng)) {
	/*$this->renderPartial($viewPrefix.'companyGMap',
	array(
		'markerLat'=>$company->map_lat,
		'markerLng'=>$company->map_lng,
		'markerTitle'=>$item->name,
	)*/

	// No clientScript because of Creators
	echo '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&key='.Yii::app()->params->google['map']['keyMapsJavaScriptApi'].'"></script>';
// 	echo '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>';
	$script = 'js/gmaps.js';
	if (Yii::app()->params['websiteMode'] == 'creators' && $this->id == 'generator')  {
		$script = $this->mapFile($script);
	}
	echo '<script type="text/javascript" src="'.$script.'"></script>';
} ?>


<?php /*<div class="smart-box">
	<h3 class="title"><?php  echo Yii::t('company', 'Company data') ?></h3>*/ ?>
<div class="smart-box title clearfix">
    <?php //echo $item->getPackageItemClass();?>
    <?php //echo $user->badge(); ?>
    <div class="pull-right"><?php echo $item->badge2(); ?></div>
    <h1><?php echo $item->name;?></h1>
    <?php $this->widget('BusinessReliableCompany', array('display' => $company->business_reliable)); ?>
    
<?php
	$tabsArray = array();
	$company->payment_type = unserialize($company->payment_type);
	$company->delivery_type = unserialize($company->delivery_type);
	if(($company->payment_type && is_array($company->payment_type))
			|| $company->payment_wire_transfer
			|| $company->payment_cash)
		$tabsArray[] = array(
			'label' => Yii::t('company', 'Preferred payment methods'),
			'content' => $this->renderPartial($viewPrefix.'_tabPayments', compact('company', 'item'), true)
		);
	if(($company->delivery_type && is_array($company->delivery_type))
			|| $company->free_delivery)
		$tabsArray[] = array(
				'label' => Yii::t('company', 'Preferred methods of delivery'),
				'content' => $this->renderPartial($viewPrefix.'_tabDelivery', compact('company', 'item'), true)
		);
	if(isset($company->map_lat) && isset($company->map_lng))
	{
		$attrs = 'style="height: 400px;"
			data-lat="'.htmlentities($company->map_lat).'"
			data-lng="'.htmlentities($company->map_lng).'"
			data-title="'.htmlentities($item->name).'"';

		$tabsArray[] = array(
				'id' => 'tab_map',
				'label' => Yii::t('company', 'Map'),
				'content' => '<div id="map-canvas" '.$attrs.'
					data-zoom="'.(Yii::app()->params->google['map']['locationZoom']).'"></div>',
				//'active' => true
		);
		if($company->street_view_active)
			$tabsArray[] = array(
				'id' => 'tab_street',
					'label' => Yii::t('company', 'Street View'),
				'content' => '<div id="pano-canvas" '.$attrs.'></div>',
				//'active' => true
							//'content' => $company->street_view
				//'<iframe src="https://www.google.com/maps/embed?pb=!1m0!3m2!1spl!2s!4v1429509783483!6m8!1m7!1sIK4QTJ7OWgtgW-iAPPFFQQ!2m2!1d51.350389!2d19.370107!3f185.64476!4f0!5f0.7820865974627469" width="528" height="400" frameborder="0" style="border:0"></iframe>'
				//'<iframe src="https://www.google.com/maps/embed?pb=!1m0!3m2!1spl!2s!4v1429509485825!6m8!1m7!1sCS1DDiAqO8qfDaY8C_vXlg!2m2!1d51.372315!2d19.374618!3f213.52432951199603!4f-5.374942367210977!5f0.7820865974627469" width="528" height="400" frameborder="0" style="border:0"></iframe>'
				//'<iframe src="https://www.google.com/maps/embed?pb=!1m0!3m2!1spl!2s!4v1429509485825!6m8!1m7!1sCS1DDiAqO8qfDaY8C_vXlg!2m2!1d51.372315!2d19.374618!3f213.52432951199603!4f-5.374942367210977!5f0.7820865974627469" width="600" height="450" frameborder="0" style="border:0"></iframe>'
				//'<iframe width="528" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://my.ctrlq.org/maps/#street|1|-92.53124999999996|5.737500000000004|51.372226|19.37464"></iframe>'
				);
	}
	if($item->youtube)
		$tabsArray[] = array(
			'label' => Yii::t('item', 'Video'),
			'content' => '<div class="video-container">'
//                            . '<iframe src="http://www.youtube.com/embed/'.$item->youtube.'" '
//                            . 'frameborder="0" allowfullscreen></iframe>'
//                            . '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$item->youtube.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                            . Html::embedYoutube($item->youtube)
                            . '</div>'
		);

	if(!empty($item->attachments))
		$tabsArray[] = array(
			'label' => 	Yii::t ('attachment', 'Files'),
			'content' => $this->renderPartial('//attachments/_attachmentList', compact('item'), true)
		);
?>

<?php
		$this->widget(
			'bootstrap.widgets.TbTabs',
			array(
				'type' => 'tabs', // 'tabs' or 'pills'
				'tabs' => array_merge(
					array(
					array (
						'label' => Yii::t ( 'company', 'Profile' ),
						'content' => $this->renderPartial ( $viewPrefix.'_tabProfile', compact ( 'company', 'item' ), true ),
						'active' => true
					),
					array (
						'label' => Yii::t ( 'company', 'Address' ),
						'content' => $this->renderPartial ( $viewPrefix.'_tabAddress', compact ( 'company', 'item' ), true ),
						),
						array(
							'label' => Yii::t('company', 'Contact'),
							'content' => $this->renderPartial($viewPrefix.'_tabContact', compact('company', 'item'), true),
						),

					),
					$tabsArray
				)
			)
		);
?>
</div>
