<?php 
/**
 * Strona produktu i usługi !!!
 *
 * @category views
 * @package product
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php 
	$class = get_class($product);
    $source = strtolower($class);
	$controller = $source.'s'; 
    
    $item = $product->item;
    $editor = $this->editorEnabled; 
    //$editor = Yii::app()->session['editor'];
    $updateUrl = $this->createGlobalRouteUrl($controller.'/update', array('id'=>$item->id));
    //$updateUrl = $this->createGlobalRouteUrl('companies/update', array('id'=>$item->id));
    //$itemUpdateUrl = $this->createGlobalRouteUrl($controller.'/partialupdate', array('model'=>'Item'));
    $fileUpdateUrl = $this->createGlobalRouteUrl($controller.'/partialupdate', array('model'=>'UserFile'));
    //$productUpdateUrl = $this->createGlobalRouteUrl($controller.'/update', array('model'=>'Product'));
    $user = $item->user;
    $company = $product->company;    
    
?>

<div class="row">
    <div class="span9">
    	<?php if($editor): ?>
    	<div class="editor-button pull-right">
        <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(
                    //'type' => 'primary',
                    'icon' => 'fa fa-wrench',
                    'label' => Yii::t('editor', 'Close'),
                	'url' => $this->createGlobalRouteUrl($controller.'/show', array('name'=>$item->alias)),
                    //'url' => $this->createGlobalRouteUrl('companies/update'),
                    //'active' => $editor,
                    'type' => 'success',                    
                )
        ); ?>                 
    	</div>
    	<?php endif;?>
    	<div class="page-header">
			<h1><i class="fa <?php echo $source == 'product' ? 'fa-shopping-cart' : 'fa-truck'; ?>"></i> <?php echo $product->item->name; ?> <small><?php echo $product->signature; ?></small></h1>
		</div>    	    	
        <div class="gallery-wrapper">            
         	<?php $this->widget('EditableGallery', array(
                    'model'     => $item,
                    'attribute' => 'files',
                    'imageSize' => 'medium',
                    'imageAlt'  => $item->name,
                    'url'       => $fileUpdateUrl,
                    //'apply'     => $this->editorEnabled
                )); ?>
		</div>    

		<hr />
		<?php echo Html::link(
			'<i class="fa fa-arrow-left"></i>&nbsp;' . Yii::t('navigation', 'Back to product page'),
			$this->createGlobalRouteUrl('products/show', array('name' => $item->alias))
		); ?>    	
    </div>
    
    <div class="span3">
        <?php if ($company) : ?>
            <?php $this->renderPartial('/companies/_companyBox', compact('company')); ?>
        <?php endif; ?>
        
        <?php $this->renderPartial('/user/_userBox', compact('user')); ?>
        <?php if (!$this->creatorsMode) {
			$this->renderPartial('/site/_qrCode', array(
				'data' => Yii::app()->createAbsoluteUrl('companies/show', array('name'=>$item->alias)),
				'filename' => $item->id,
				'class' => 'Item',
				'id' => $item->id
			));
		} ?>
    </div>
</div>
