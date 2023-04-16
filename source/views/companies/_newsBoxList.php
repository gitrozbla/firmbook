
<?php $link =  $this->createGlobalRouteUrl('/news/list/', array(
	        'name' => $company->item->alias,        
	)); ?> 
<?php $badge = $this->widget(
    'bootstrap.widgets.TbBadge',
    array(
	    'type' => 'info',	    
	    'label' => $itemCount,
    	'htmlOptions' => array('style' => 'margin-left: 10px;'),    	
	), 
	true
); ?> 
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => Html::link(Yii::t('news', 'News'), $link).' '.$badge,
        'headerIcon' => 'fa fa-file-text-o',
        'htmlOptions' => array('class' => 'transparent'),
    	'htmlHeaderOptions' => array(
    			'class' => 'package-item',
    			'style' => $company->item->getItemStyle()
    	)
    	//'htmlHeaderOptions' => array('class' => $company->item->getPackageItemClass()),    	
    )
); ?>
<?php
		$itemsCount = 3;
	    $this->widget('bootstrap.widgets.TbListView', array(
	        'id' => 'item-list',
	    	'dataProvider' => $company->newsDataProvider($itemsCount),
	        //'dataProvider' => Item::model()->searchDataProvider($search, $category),
	        'itemView' => '_newsBoxItem',
	        'itemsTagName' => 'ul',
	    	//'enablePagination' => false,
	    	'template' => '{items}', 
	    	//'viewData' => compact('link', 'itemsCount'),
	        'htmlOptions' => array(
	    		'class' => 'medium-list',
	            //'class' => 'medium-list list-view',
	        )
	    ));
?> 
<?php $this->endWidget(); ?>