<?php 
/**
 * Strona usługi.
 *
 * @category views
 * @package service
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php 
    $item = $service->item; 
    $editor = Yii::app()->session['editor'];
    $updateUrl = $this->createUrl('services/update', array('id'=>$item->id));
    $itemUpdateUrl = $this->createUrl('services/update', array('model'=>'Item'));
    $serviceUpdateUrl = $this->createUrl('services/update', array('model'=>'Service'));
    $user = $item->user;
    $company = $service->company;
?>

<div class="row">
    <div class="span9">
    	<?php if($editor): ?>
    	<div class="editor-button pull-right">
        <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(                    
                    'icon' => 'fa fa-wrench',
                    'label' => Yii::t('editor', 'Edit'),
                	'url' => $updateUrl,                    
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
