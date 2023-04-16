<?php Yii::app()->clientScript->registerScript(
        'item-list-thumbnail-hover',
        "$('#item-list .thumbnail').popover({"
            . "'trigger': 'hover',"
            . "'html': true"
        . "});"
        ); ?>
<?php switch($type) {
    case 'product':
        $controller = 'products';
        $title = 'Products';
        $icon = 'shopping-cart';
        $noItemsMessage = 'No active products.';
        $itemsType = 'p';
        break;
    case 'service':
        $controller = 'services';
        $title = 'Services';
        $icon = 'truck';
        $noItemsMessage = 'No active services.';
        $itemsType = 's';
        break;    
} ?>
<?php 
//$link =  $this->createUrl('/companies/'.$controller.'/', array(
$link =  $this->createUrl('/companies/offer/', array(
        'name' => $company->item->alias,
		'type' => $type
        
));
/*$link =  $this->createUrl('/categories/show/', array(
        'context' => Search::getContextOption($action, $type),
        'username' => $user->username,
));*/
 ?>
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => Html::link(Yii::t($controller, $title), $link),
        'headerIcon' => 'fa fa-'.$icon,
        'htmlOptions' => array('class' => 'transparent')
    )
);?>
    <ul class="small-list" id="item-list">
        <?php $data = Company::model()->companyDataProvider($company->item_id, $itemsType, 3)->getData(); ?>
        <?php if (empty($data)) : ?>
            <?php echo Yii::t($controller, $noItemsMessage); ?>
        <?php endif; ?>
        <?php foreach($data as $item) : ?>
        <li<?php /*if ($item->cache_package_id) : ?> class="package-item-<?php echo $item->package->css_name; ?>"<?php endif;*/ ?>>
            <?php /*echo Html::link(
                    ($item->thumbnail
                        ?  Html::image($item->thumbnail->generateUrl('small'), $item->name).' '
                        : '')
                        .$item->name, 
                    $this->createUrl($controller.'/show', array(
                        'name' => $item->alias))
                    ); */?>
        	<div class="inline column-thumbnail">
	        <?php if ($item->thumbnail != null) : ?>
	            <?php echo Html::link(
	                    Html::image($item->thumbnail->generateUrl('small'), $item->name),
	                    $this->createUrl($controller.'/show', array('name' => $item->alias)),
	                    array(
	                        'class' => 'thumbnail',
	                        'data-content' => Html::image(
	                                $item->thumbnail->generateUrl('large'), 
	                                '',
	                                array('style'=>'height: 200px;'))
	                            .'<br />'.$item->name,
	                        )); ?>
	        <?php endif; ?>
	    	</div>
        </li>
        <?php endforeach; ?>
        <li>
            <?php echo Html::link('...', $link); ?>
        </li>
    </ul>

<?php $this->endWidget(); ?>