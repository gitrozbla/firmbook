<?php 
/**
 * Element na liście (produkt, usługa, firma).
 *
 * @category views
 * @package main
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<div class="row-fluid home-thumbnails">
    <?php $i=0; ?>
    <?php foreach($data as $item) : ?>
    <?php 
    	
    	/*if($itemsController == 'companies')
    	{
    		if($item->alias != $item->id &&  PackageControl::getValue($item->cache_package_id, 'subdomain'))
				$url = $this->createUrl('companies/show', array('subdomain'=>$item->alias)); 
			//else
			//	$url = $this->createUrl('companies/show', array('name'=>$item->alias));	
    	}*/
    	if(!isset($url))
    		$url = $this->createUrl($itemsController.'/show', array(
                    'name'=>Yii::t('item-'.$item->id, $item->alias, array(), 'dbMessages')
                    ));
    
    ?>
        <div class="span3<?php if ($i % 4 == 0) : ?> left-0<?php endif; ?>">
            <?php echo Html::link(
                '<div class="image-box">'.
                    ($item->thumbnail != null 
                        ? Html::image($item->thumbnail->generateUrl('small')) 
                        : '<i class="fa fa-'.$itemsIcon.'"></i>').
                '</div>'
                    .Yii::t('item-'.$item->id, $item->name, array(), 'dbMessages'),
                    $url
                /*$this->createUrl($itemsController.'/show', array(
                    'name'=>Yii::t('item-'.$item->id, $item->alias, array(), 'dbMessages')
                    ))*/,
                array(
                    'class' => 'thumbnail'
                )
            ); ?>
        </div>
        <?php $i++; ?>
        <?php unset($url); ?>
    <?php endforeach; ?>
</div>
