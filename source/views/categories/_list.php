<?php 
/**
 * Lista elementów.
 *
 * @category views
 * @package category
 * @author 
 * @copyright (C) 2015 
 */ 

?>
<?php Yii::app()->clientScript->registerScript(
        'item-list-thumbnail-hover',
        "$('#item-list .thumbnail').popover({"
            . "'trigger': 'hover',"
            . "'html': true"
        . "});"
        ); ?>

<?php 
    $type = $search->type;
    $class = ucfirst($type);
    switch($type) {
        case 'product':
            $view = '/products/_productListItem';
            $controller = 'products';
            $sortableAttributes = array('name', 'price', 'views');
            break;
        case 'service':
            $view = '/services/_serviceListItem';
            $controller = 'services';
            $sortableAttributes = array('name', 'price', 'views');
            break;
        case 'company':
            $view = '/companies/_companyListItem';
            $controller = 'companies';
            $sortableAttributes = array('name', 'views');
            break;
    }
?>

<?php if (Yii::app()->user->checkAccess(ucfirst($controller).'.add')) : ?>    
    <?php $this->widget(
        'Button',
        array(
            'label' => Yii::t($type, 'Add new '.$type),
            'type' => 'success',
            'icon' => 'fa fa-plus',
        	'url' => $this->createUrl($controller.'/add'),
            //'url' => $this->createUrl($controller.'/add'),
            //'disabled' => $addButtonDisabled,
        )
    ); ?>           
<?php endif; ?>
<?php if(!Yii::app()->user->isGuest && isset($category)) : ?>	
	<?php $this->widget(
        'Button',
        array(
            'id' =>'follow-btn-'.$category->id,
            'buttonType' => 'ajaxLink',
            'url' => $this->createUrl('follow/add', array('id'=>$category->id, 'type'=>Follow::ITEM_TYPE_CATEGORY)),
            //'label' => Yii::t('common','Favorite'),
            'type' => Follow::model()->exists(
                'user_id=:user_id and item_id=:item_id and item_type=:iten_type',
                array(':user_id'=>Yii::app()->user->id, ':item_id'=>$category->id, ':iten_type'=>Follow::ITEM_TYPE_CATEGORY))
                ? 'success' : '',
            'icon' => 'fa fa-eye',
            'ajaxOptions' => array(
                'dataType' => 'json',
                'success' => 'function(data) {    								    				    			
                    $("#btn-follow").html(data.button);	
                    if(data.scenario)
                        $("#follow-btn-'.$category->id.'").addClass("btn-success");
                    else	
                        $("#follow-btn-'.$category->id.'").removeClass("btn-success");		    				
                }'                           			
            ),
            'htmlOptions' => array('style'=>'margin-left: 5px;', 'class'=>'pull-right'),                                				    	
        )
    ); ?>
<?php endif; ?>

<br />

<?php 
// 	$itemsDataProvider = Item::model()->searchDataProvider($search, $category);
	$items = $itemsDataProvider->getData();
	if(!count($items))
		$this->setRobotsIndex(false);	
?>

<?php $this->renderPartial('/site/listGMap', compact('items', 'type')); ?>

<?php
	if (!Yii::app()->user->isGuest)
		$elistItems = Elist::userItems(Yii::app()->user->id);
	else	
		$elistItems = NULL;
    
    $markerIndexes = [];
    $markerIndex = 0;
    for($i=0;$i<count($items);++$i)
    {
        if($type == 'product')
        {    
            if(!isset($items[$i]->product->company) || !$items[$i]->product->company->map_lat || !$items[$i]->product->company->map_lng)
                continue;
            $markerIndexes[$items[$i]->id] = $markerIndex++;
        } elseif($type == 'service')
        {    
            if(!isset($items[$i]->service->company) || !$items[$i]->service->company->map_lat || !$items[$i]->service->company->map_lng)
                continue;
            $markerIndexes[$items[$i]->id] = $markerIndex++;
        } elseif($type == 'company')
        {    
            if(!$items[$i]->company->map_lat || !$items[$i]->company->map_lng)
                continue;
            $markerIndexes[$items[$i]->id] = $markerIndex++;
        }
        
    }

//    $this->widget('bootstrap.widgets.TbListView', array(
    $this->widget('AppTbListView', array(
        'id' => 'item-list',
    	'dataProvider' => $itemsDataProvider,        
        'itemView' => $view,
        'itemsTagName' => 'ul',
        'htmlOptions' => array(
            'class' => 'big-list list-view',
        ),        
        'template'=>'{summary}{sorter}{items}{pager}',
        'sortableAttributes' => $sortableAttributes,
        'enableSorting' => true,
        'viewData' => array('elistItems' => $elistItems, 'markerIndexes' => $markerIndexes),
//     	'url' => '/categories/show'        
//     	'route' => '/categories/show'
//     	'ajaxUrl' => $search->createUrl(null, array(Search::getContextUrlType($search->type).'_context'=>Search::getContextUrlAction($search->action, $search->type), 'name'=>$category->alias))
		'ajaxUpdate' => false,
    ));
?>

