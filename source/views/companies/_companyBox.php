<?php if ($company->item->active) : ?>
    <?php $box = $this->beginWidget(
        'bootstrap.widgets.TbBox',
        array(
            'title' => Html::link(
                $company->item->name, 
                $this->createGlobalRouteUrl('companies/show', array(
                    'name' => $company->item->alias))
                ).$this->widget('BusinessReliableCompany', array('display' => $company->business_reliable, 'asBadge'=>true), true),
            'headerIcon' => 'fa fa-building-o',
            'htmlOptions' => array('class' => 'transparent fixed-width-icon'),
        	'htmlHeaderOptions' => array(
                'class' => 'package-item',
                'style' => $company->item->getItemStyle()
        	)
            //'htmlHeaderOptions' => array('class' => $company->item->getPackageItemClass()),
        )
    );?>
        <?php if ($company->item->thumbnail_file_id): ?>
            <div class="text-center bottom-10">
                <?php echo Html::link(
                    Html::image(
                            $company->item->thumbnail->generateUrl('medium'),
                            $company->item->name), 
                    $this->createGlobalRouteUrl('companies/show', array(
                        'name' => $company->item->alias))
                    ); ?>
            </div>
        <?php endif; ?>
        <?php if ($company->short_description): ?>
            <p>
                <?php echo $company->short_description; ?>
            </p>
            <hr />
        <?php endif; ?>
		
		<?php if ($company->item->www): ?>
            <i class="fa fa-external-link"></i>
            <?php $this->widget(
                    'application.components.widgets.TextOverflowScroll',
                    array(
                        'text' => Html::link(str_replace(array('http://', 'https://'), '', $company->item->www),
                        			$company->item->www,
                        			array('target' => '_blank'))
                    )
            ); ?><br />
        <?php endif; ?>

        <?php if ($company->email): ?>
            <i class="fa fa-envelope"></i> 
            <?php $this->widget(
                    'application.components.widgets.TextOverflowScroll', 
                    array(
                        'text' => Html::link($company->email, 'mailto:'.$company->email),
                    )
            ); ?><br />
        <?php endif; ?>

        <?php if ($company->phone): ?>
            <i class="fa fa-phone"></i> 
            <?php echo Html::link(
                    $company->phone, 
                    'tel:'.str_replace(array(' ', '-', '&nbsp;'), '', $company->phone)
                    ); ?><br />
        <?php endif; ?>

    <?php $this->endWidget(); ?>
<?php endif; ?>