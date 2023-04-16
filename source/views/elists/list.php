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
                <?php if (isset($resource->cache_type)) : ?>
                    <?php if ($resource && $resource->cache_type == 'c' ) : ?>
                            <?php $this->renderPartial('/companies/_companyBox', array('company'=>$resource->company)); ?>
                    <?php elseif ($resource && $resource->cache_type == 's' ) : ?>	
                            <?php $this->renderPartial('/products/_productBox', array('product'=>$resource->service)); ?>
                    <?php else: ?>
                            <?php $this->renderPartial('/products/_productBox', array('product'=>$resource->product)); ?>
                    <?php endif; ?>
            	<?php else: ?>
            		<?php $this->renderPartial('/user/_userBox', array('user'=>$resource)); ?>
            	<?php endif; ?>
            </div>
        </div>
        <div class="span9">
            <div class="page-header">
                <h1><i class="fa <?php echo $elist->elistFaIconClass(); ?>"></i> <?php echo  $elist->inverseListName() ?></h1>
            </div>
    <?php else: ?>
    	<div class="span12">
            <div class="page-header">
                <h1><i class="fa <?php echo $elist->elistFaIconClass(); ?>"></i> <?php echo  $elist->elistName() ?></h1>
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
