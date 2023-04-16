<?php 
/**
 * Strona produktu.
 *
 * @category views
 * @package product
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php 
    $item = $product->item;
    $editor = $this->editorEnabled; 
    //$editor = Yii::app()->session['editor'];
    $updateUrl = $this->createUrl('products/update', array('id'=>$item->id));
    //$updateUrl = $this->createUrl('companies/update', array('id'=>$item->id));
    $itemUpdateUrl = $this->createUrl('products/partialupdate', array('model'=>'Item'));
    $productUpdateUrl = $this->createUrl('products/update', array('model'=>'Product'));
    $user = $item->user;
    $company = $product->company;
?>

<div class="row">
    <div class="span3">
    <?php $box = $this->beginWidget(
        'bootstrap.widgets.TbBox',
        array(
            'title' => Html::link(
                    $product->item->name, 
                    $this->createUrl('companies/show', array(
                        'name' => $product->item->alias))
                    ),
            'headerIcon' => 'fa fa-building-o',
            'htmlOptions' => array('class' => 'transparent'),
            'htmlHeaderOptions' => array('class' => $product->item->getPackageItemClass()),
        )
    );?>
    	<?php /*(if ($this->editorEnabled || $item->thumbnail != null) : */ ?>
    	<?php if ($this->editorEnabled) : ?>    	
            <!-- <div class="thumbnail pull-left right-30 bottom-10"> -->
            <div class="thumbnail text-center bottom-10">
                <?php $this->widget('EditableImage', array(
                    'model'     => $item,
                    'attribute' => 'thumbnail_file_id',
                    'imageSize' => 'small',
                    'imageAlt'  => $item->name,
                    'url'       => $itemUpdateUrl,
                    'apply'     => $this->editorEnabled,
                )); ?>
            </div>
            
        <?php elseif ($product->item->thumbnail_file_id): ?>
            <div class="text-center bottom-10">
                <?php echo Html::link(
                    Html::image(
                            $product->item->thumbnail->generateUrl('medium'),
                            $product->item->name), 
                    $this->createUrl('companies/show', array(
                        'name' => $product->item->alias))
                    ); ?>
            </div>
        <?php endif; ?>
	<?php $this->endWidget(); ?>
	</div>
    <div class="span6">
    	<?php if($editor): ?>
    	<div class="editor-button pull-right">
        <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(
                    //'type' => 'primary',
                    'icon' => 'fa fa-wrench',
                    'label' => Yii::t('editor', 'Edit'),
                	'url' => $updateUrl,
                    //'url' => $this->createUrl('companies/update'),
                    //'active' => $editor,
                    'type' => 'success',
                    
                )
        ); ?>        
    	</div>
    	<?php endif;?>
    	<h1><?php echo $item->name; ?></h1>
        <?php $box = $this->beginWidget(
		    'bootstrap.widgets.TbBox',
		    array(
		        'title' => Yii::t('product', 'Description'),
		        'headerIcon' => 'fa fa-paperclip',
		        'htmlOptions' => array('class' => 'transparent'),
		        'htmlHeaderOptions' => array('class' => $user->getPackageItemClass()),
		    )
		);?>
		<?php echo $item->description; ?>
		<?php $this->endWidget(); ?>
    </div>
    
    <div class="span3">
        <?php if ($company) : ?>
            <?php $this->renderPartial('/companies/_companyBox', compact('company')); ?>
        <?php endif; ?>
        
        <?php $this->renderPartial('/user/_userBox', compact('user')); ?>
    </div>
</div>
