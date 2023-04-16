<?php 
/**
 * Lista produktów i usług firmy.
 *
 * @category views
 * @package companies
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
    $class = ucfirst($type);
    switch($type) {
        case 'product':
            $view = '/products/_productListItem';
            $controller = 'products';
            $itemsType = 'p';
            break;
        case 'service':
            $view = '/services/_serviceListItem';
            $controller = 'services';
            $itemsType = 's';
            break;        
    }
?>

<?php //if (Yii::app()->user->checkAccess(ucfirst($controller).'.add')) : ?>
<?php if($this->editorEnabled) : ?>
<div class="editor-button pull-right">    
    <?php $this->widget(
        'Button',
        array(
            'label' => Yii::t($type, 'Add new '.$type),
            'type' => 'success',
            'icon' => 'fa fa-plus',
        	'url' => $this->createGlobalRouteUrl($controller.'/add',
        		array('company'=>$company->item_id)),
            //'url' => $this->createUrl($controller.'/add'),
            //'disabled' => $addButtonDisabled,
        )
    ); ?>           
</div>    
<div class="clearfix"></div>
<?php endif; ?>
<?php
	if (!Yii::app()->user->isGuest)
		$elistItems = Elist::userItems(Yii::app()->user->id);
	else	
		$elistItems = NULL;
    $this->widget('bootstrap.widgets.TbListView', array(
        'id' => 'item-list',
    	'dataProvider' => Company::model()->companyDataProvider($company->item_id, $itemsType, false, !$this->editorEnabled),
        //'dataProvider' => Item::model()->searchDataProvider($search, $category),
        'itemView' => $view,
        'itemsTagName' => 'ul',
    	'viewData' => array('companyPage'=>1, 'elistItems' => $elistItems),
        'htmlOptions' => array(
            'class' => 'big-list list-view',
        )
    ));
?>