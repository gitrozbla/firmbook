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
            <?php $this->renderPartial('//companies/_companyBox', array('company'=>$resource->company)); ?>
            </div>
        </div>
        <div class="span9">
            <div class="page-header">
                <h1><i class="fa fa-thumbs-up"></i> <?php echo Yii::t('LikedislikeModule.main', 'Liked by') ?></h1>
            </div>
    <?php else: ?>
    	<div class="span12">
            <div class="page-header">
                <h1><i class="fa fa-thumbs-up"></i> <?php echo Yii::t('LikedislikeModule.main', 'Liked by') ?></h1>
            </div>    	 
    <?php endif; ?>
            <div class="row">
            <?php
                $this->widget('bootstrap.widgets.TbListView', array(
                    'id' => 'item-list',
                    'dataProvider' => $dataProvider,                    
                    'itemView' => $itemView,    
                    'htmlOptions' => array(
                        'class' => 'big-list list-view',
                    )
                ));
            ?>
            </div>
	</div>
</div>
