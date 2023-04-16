<?php 
/**
 * Formularza dodania i edycji załącznika
 *
 * @category views
 * @package attachment
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php 
	$class = get_class($itemCPS);
    $source = strtolower($class);
	//$controller = $source.'s'; 
    
    //$item = $product->item;
    $editor = $this->editorEnabled; 
    
    $user = $item->user;
    if($class == 'Company')
    	$company = $itemCPS;
    else	
    	$company = $itemCPS->company;    
    
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
                	'url' => $this->createUrl($controller.'/show', array('name'=>$item->alias)),
                    //'url' => $this->createUrl('companies/update'),
                    //'active' => $editor,
                    'type' => 'success',                    
                )
        ); ?>                 
    	</div>
    	<?php endif;?>
    	
    	<div class="page-header">
    		<?php if($class == 'Product' || $class == 'Service'): ?>
			<h1><i class="fa <?php echo $source == 'product' ? 'fa-shopping-cart' : 'fa-truck'; ?>"></i> <?php echo $item->name; ?> <small><?php echo $itemCPS->signature; ?></small></h1>
			<?php else: ?>
			<h1><i class="fa fa-building-o"></i> <?php echo $item->name; ?></h1>
			<?php endif; ?>
		</div>    	    	
		
		<?php $this->renderPartial('/attachments/_attachmentForm', 
				array('attachment'=>$attachment, 'item'=>$item, 'controller'=>$controller)) ?>
               	
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
