<?php
/*
 * elista
 *
 * @category views
 * @package pages
 * @author
 * @copyright (C) 2014
 */

if(!isset($resource))
    $resource = null;
?>
<div class="row">
    <?php if ($resource) : ?>
	<div class="left-frame">	    
            <div class="span3 pull-left">
            <?php $this->renderPartial('/companies/_companyBox', array('company'=>$resource->company)); ?>
            </div>
        </div>
        <div class="span9">
            <div class="page-header">
                <h1><i class="fa fa-eye"></i> <?php echo Yii::t('follow', 'Followers') ?></h1>
            </div>
    <?php else: ?>
    	<div class="span12">
            <div class="page-header">
                <h1><i class="fa fa-eye"></i> <?php echo Yii::t('follow', 'Observed') ?></h1>
            </div>    	 
    <?php endif; ?>
            <div class="row">
            <?php
                $this->widget('bootstrap.widgets.TbListView', array(
                    'id' => 'item-list',
                    'dataProvider' => $dataProvider,
                    //'dataProvider' => Item::model()->searchDataProvider($search, $category),
                    'itemView' => $itemView,
    // 	        'itemsTagName' => 'ul',
    // 	    	'viewData' => array('companyPage'=>1, 'elistItems' => $elistItems),
                    'htmlOptions' => array(
                        'class' => 'big-list list-view',
                    )
                ));
            ?>
            </div>
	</div>
</div>
