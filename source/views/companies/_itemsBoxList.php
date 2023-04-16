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
        $view = '/products/_productListItem';
        break;
    case 'service':
        $controller = 'services';
        $title = 'Services';
        $icon = 'truck';
        $noItemsMessage = 'No active services.';
        $itemsType = 's';
        $view = '/services/_serviceListItem';
        break;    
} ?>
<?php 
//$link =  $this->createUrl('/companies/'.$controller.'/', array(
$link =  $this->createGlobalRouteUrl('/companies/offer/', array(
        'name' => $company->item->alias,
		'type' => $type
        
));
 ?> 
<?php $badge = $this->widget(
    'bootstrap.widgets.TbBadge',
    array(
	    'type' => 'info',
	    // 'default', 'success', 'info', 'warning', 'danger'
	    'label' => $itemCount,
    	'htmlOptions' => array('style' => 'margin-left: 10px;'),
    	//'htmlOptions' => array('style' => 'float: right;'),
	), 
	true
);
?> 
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => Html::link(Yii::t($controller, $title), $link).' '.$badge,
        'headerIcon' => 'fa fa-'.$icon,
        'htmlOptions' => array('class' => 'transparent'),
    	'htmlHeaderOptions' => array(
    			'class' => 'package-item',
    			'style' => $company->item->getItemStyle()
    	)
    	//'htmlHeaderOptions' => array('class' => $company->item->getPackageItemClass()),
    	/*'encodeLabel' => false,
    	'badge' => array(
                                'label' => '10',
                                'type' => 'info',
                                ),*/
    )
);?>
	<?php
		$itemsCount = 3;
	    $this->widget('bootstrap.widgets.TbListView', array(
	        'id' => 'item-list',
	    	'dataProvider' => Company::model()->companyDataProvider($company->item_id, $itemsType, $itemsCount),
	        //'dataProvider' => Item::model()->searchDataProvider($search, $category),
	        'itemView' => '_itemsBoxItem',
	        'itemsTagName' => 'ul',
	    	//'enablePagination' => false,
	    	'template' => '{items}', 
	    	'viewData' => compact('link', 'itemsCount', 'type'),
	        'htmlOptions' => array(
	    		'class' => 'medium-list',
	            //'class' => 'medium-list list-view',
	        )
	    ));
	?> 
<?php $this->endWidget(); ?>