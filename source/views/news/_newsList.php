<?php 
/**
 * Lista aktualności.
 *
 * @category views
 * @package news
 * @author 
 * @copyright (C) 2015 
 */ 
?>


<?php //if (Yii::app()->user->checkAccess(ucfirst($controller).'.add')) : ?>
<?php if($this->editorEnabled) : ?>
<div class="editor-button pull-right">    
    <?php $this->widget(
        'Button',
        array(
            'label' => Yii::t('news', 'Add article'),
            'type' => 'success',
            'icon' => 'fa fa-plus',
        	'url' => $this->createGlobalRouteUrl('news/add',
        		array('company'=>$company->item_id)),
            //'url' => $this->createGlobalRouteUrl($controller.'/add'),
            //'disabled' => $addButtonDisabled,
        )
    ); ?>           
</div>    
<div class="clearfix"></div>
<?php endif; ?>
<?php
	/*if (!Yii::app()->user->isGuest)
		$elistItems = Elist::userItems(Yii::app()->user->id);
	else	
		$elistItems = NULL;*/
    $this->widget('bootstrap.widgets.TbListView', array(
        'id' => 'item-list',
    	'dataProvider' => News::newsDataProvider($company->item_id),
    	//'dataProvider' => $news->newsDataProvider(),    	        
        'itemView' => '_newsListItem',
        'itemsTagName' => 'ul',
    	'template'=>'{summary}{sorter}{items}{pager}',
    	'sortableAttributes'=>array('date'),
    	'enableSorting' => true,
    	//'viewData' => array('companyPage'=>1, 'elistItems' => $elistItems),
        'htmlOptions' => array(
            'class' => 'big-list list-view',
        )
    ));
?>