<?php if ($product->item->active) : ?>
    <?php $box = $this->beginWidget(
        'bootstrap.widgets.TbBox',
        array(
            'title' => Html::link(
                    $product->item->name, 
                    $this->createGlobalRouteUrl('products/show', array(
                        'name' => $product->item->alias))
                    ),
            'headerIcon' => 'fa fa-'.Item::faIconClass($product->item->cache_type),
            'htmlOptions' => array('class' => 'transparent fixed-width-icon'),
        	'htmlHeaderOptions' => array(
        			'class' => 'package-item',
        			'style' => $product->item->getItemStyle()
        	)
            //'htmlHeaderOptions' => array('class' => $company->item->getPackageItemClass()),
        )
    );?>
        <?php if ($product->item->thumbnail_file_id): ?>
            <div class="text-center bottom-10">
                <?php echo Html::link(
                    Html::image(
                            $product->item->thumbnail->generateUrl('medium'),
                            $product->item->name), 
                    $this->createGlobalRouteUrl('products/show', array(
                        'name' => $product->item->alias))
                    ); ?>
            </div>
        <?php endif; ?>
        
		
		
    <?php $this->endWidget(); ?>
<?php endif; ?>