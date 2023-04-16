<?php 
/**
 * Tłumaczenia dla modelu firmy.
 *
 * @category views
 * @package company
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
    	case 'companies':
    		$view = '/companies/_companyListItem';
    		$controller = 'companies';
    		$itemsType = 'c';
    		$itemType = 'company';
    		break;
        case 'products':
            $view = '/products/_productListItem';
            $controller = 'products';
            $itemsType = 'p';
            $itemType = 'product';
            break;
        case 'services':
            $view = '/services/_serviceListItem';
            $controller = 'services';
            $itemsType = 's';
            $itemType = 'service';
            break;        
    }
?>
<div class="row">
	<div class="span3">
		<?php $this->renderPartial('/user/_userBox', compact('user')); ?>
		<?php $this->renderPartial('_itemsMenu', compact('user')); ?>
	</div>
	<div class="span9">
		<?php if($user->id == Yii::app()->user->id) : ?>
			<div class="editor-button pull-right">    
			    <?php $this->widget(
			        'Button',
			        array(
			            'label' => Yii::t($type, 'Add new '.$itemType),
			            'type' => 'success',
			            'icon' => 'fa fa-plus',
			        	'url' => $this->createUrl($controller.'/add'),
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
		    	'dataProvider' => $user->itemsDataProvider($itemsType),
		        //'dataProvider' => Item::model()->searchDataProvider($search, $category),
		        'itemView' => $view,
		        'itemsTagName' => 'ul',
		    	'template'=>'{summary}{sorter}{items}{pager}',
		    	'sortableAttributes'=>array('date'),
		    	'enableSorting' => true,
		    	'viewData' => array('companyPage'=>1, 'elistItems' => $elistItems),
		        'htmlOptions' => array(
		            'class' => 'big-list list-view',
		        )
		    ));
		?>
	</div>
</div>