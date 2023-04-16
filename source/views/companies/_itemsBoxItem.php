<?php 
/**
 * Produkt na liście w box-ie na podstronie firmy.
 *
 * @category views
 * @package product
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php
	switch($type) {
    case 'product':
        $controller = 'products';
        break;
    case 'service':
        $controller = 'services';
        break;
	}
	$url = $this->createGlobalRouteUrl($controller.'/show', array(
    	'name'=>$data->alias)); //Yii::t('item-'.$data->id, $data->alias, array(), 'dbMessages'))); 
	$updateUrl = $this->createGlobalRouteUrl($controller.'/update', array('id'=>$data->id));
?>
<li>
    <div class="inline column-thumbnail">
        <?php if ($data->thumbnail != null) : ?>
            <?php echo Html::link(
                    Html::image($data->thumbnail->generateUrl('small'), $data->name),
                    $url,
                    array(
                        'class' => 'thumbnail',
                        'data-content' => Html::image(
                                $data->thumbnail->generateUrl('large'), 
                                '',
                                array('style'=>'height: 200px;'))
                            .'<br />'.$data->name,
                        )); ?>
        <?php endif; ?>
    </div>
    <div class="inline column-title">        
        <h2>
            <?php echo Html::link(
                    !empty($data->name) 
                        ? $data->name//Yii::t('item-'.$data->id, $data->name, array(), 'dbMessages')
                        : '('.Yii::t('item', 'No name').')',
                    $url
                ); ?><br/>
            <?php if (!$data->active) :?>
                <span class="text-error">(<?php echo Yii::t('item', 'Inactive'); ?>)</span>
            <?php endif; ?>
        </h2>
        <?php if($data->$type->promotion_price) : ?>
        	<?php $this->widget('bootstrap.widgets.TbLabel',
					array(
						'type' => 'warning',
						'label' => $data->$type->promotion_price.' '.(isset($data->$type->currency->name) ? $data->$type->currency->name : '')				
					)); ?>
		<?php elseif($data->$type->price): ?>
			<?php $this->widget('bootstrap.widgets.TbLabel',
					array(
						'type' => 'success',
						'label' => $data->$type->price.' '.(isset($data->$type->currency->name) ? $data->$type->currency->name : '')				
					)); ?>			
		<?php endif; ?>			
    </div>
    <div class="inline column-contact"></div>
</li>
<?php if($index==$itemsCount-1) : ?>
<li>
	<?php echo Html::link('...', $link); ?>
</li>
<?php endif; ?>
<?php /*<hr />*/ ?>