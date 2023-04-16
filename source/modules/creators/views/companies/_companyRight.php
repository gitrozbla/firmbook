<?php 
/*
 * lewa kolumna na podstronach firmy
 */

$item = $company->item;

?>

<div class="well">   	
    <?php if ($company->item->thumbnail_file_id) {
        echo '<p>'.Html::image($company->item->thumbnail->generateUrl('medium')).'</p>';
    } ?>
    
    <h3>
        <?php echo Html::link(
                $company->item->name, 
//                 $this->createFirmbookUrl("companies/show", array("name"=>$company->item->alias)),
        		$this->createGlobalRouteUrl("companies/show", array("name"=>$company->item->alias)),
                array('target' => '_blank')); ?>
    </h3>

    <?php if ($company->short_description): ?>
        <p>
            <?php echo $company->short_description; ?>
        </p>
    <?php endif; ?>
        
    <?php if (!$company->hide_email && $company->email): ?>
        <i class="fa fa-envelope"></i>&nbsp;<?php $this->widget(
                'application.components.widgets.TextOverflowScroll', 
                array(
                    'text' => Html::link($company->email, 'mailto:'.$company->email),
                )
        ); ?><br />
    <?php endif; ?>

    <?php if ($company->phone): ?>
        <i class="fa fa-phone"></i>&nbsp;<?php echo Html::link(
                $company->phone, 
                'tel:'.str_replace(array(' ', '-', '&nbsp;'), '', $company->phone)
                ); ?><br />
    <?php endif; ?>
                    
</div>

<?php /*
    $productCount = Product::model()->countBySql('
            SELECT COUNT(*) FROM tbl_product p
            LEFT JOIN tbl_item i ON i.id=p.item_id
            WHERE p.company_id=:company_id
                AND i.active=1', array(
            ':company_id' => $company->item_id));
    $serviceCount = Service::model()->countBySql('
            SELECT COUNT(*) FROM tbl_service p
            LEFT JOIN tbl_item i ON i.id=p.item_id
            WHERE p.company_id=:company_id
                AND i.active=1', array(
            ':company_id' => $company->item_id));
    
    $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array(
        'title' => Yii::t('company', 'Offer'),
        'content' => $this->widget(
            'bootstrap.widgets.TbMenu',
            array(
                'encodeLabel' => false,
                'items' => array(
                    array(                            	
                        'label' => '<i class="fa fa-shopping-cart"></i> '
                                .Yii::t('company', 'Products')
                                .' <span class="badge">'.$productCount.'</span>',
                        'url' => $this->createFirmbookUrl('/companies/offer/', array(
                            'name' => $item->alias,
                            'type' => 'product')),
                    ),
                    array(
                        'label' => '<i class="fa fa-truck"></i> '
                                .Yii::t('company', 'Services')
                                .' <span class="badge">'.$serviceCount.'</span>',
                        'url' => $this->createFirmbookUrl('/companies/offer/', array(
                            'name' => $item->alias,
                            'type' => 'service')),
                    )
                ),                    	   	
            ),
            true
        ),
    )
); ?>

    <?php 
        $count = UserFile::model()->countByAttributes(array(
            'class' => 'Item',
            'data_id' => $company->item_id
        )); 
        if ($count > 0 ) {
            echo '<hr />'.Html::link(
                    Yii::t('CreatorsModule.companies', 'See photos gallery'),
                    $this->createFirmbookUrl('/companies/gallery', array(
                        'name' => $item->alias)),
                    array('target' => '_blank')
            );
        }
    ?>

<?php $this->endWidget(); ?>

<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array('title' => Yii::t('categories', 'Categories'))
);?>
    <strong><?php echo $item->category->nameLocal; ?></strong>
    <?php if (!empty($item->additionalCategories)) {
        echo '<hr />'.Yii::t('common', 'and').': ';
        $categories = array();
        foreach($item->additionalCategories as $category) {
            $categories []= $category->nameLocal;
        }
        echo implode(', ', $categories);
    } ?>
<?php $this->endWidget();*/ ?>			    

    
<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbBox',
    array('title' => false)
);?>
    <p class="text-center">
        <?php /*echo Html::link(
                Yii::t('CreatorsModule.companies', 
                        'Login to your Firmbook account to edit company data.'),
                $this->createFirmbookUrl('/companies/update', array('id'=>$item->id)),
                array('target'=>'_blank'));*/ ?>
		<?php echo Html::link(
                Yii::t('CreatorsModule.companies',
                        'Click here to edit company data.'),
				$this->createGlobalRouteUrl('companies/update', array('id'=>$company->item->id))
//                 $this->createGlobalRouteUrl('companies/show', array(
// 										'name' => $item->alias
// 								))
				); ?>
    </p>
<?php $this->endWidget(); ?>