<?php 
/**
 * Galeria zdjęć firmy.
 *
 * @category views
 * @package company
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php 
	$editor = Yii::app()->session['editor'];
    $item = $company->item; 
    $fileUpdateUrl = $this->createUrl('companies/partialupdate', array('model'=>'UserFile'));
?>

<div class="row">

	<?php require '_companyPanel.php'; ?>
	
	<?php $this->renderPartial('_companyLeft', compact('company', 'item')); ?>
		
	<div class="right-frame">	
		
		<div class="span9 gallery-wrapper">
		
		<?php $this->renderPartial('/site/_editorButton'); ?>
		<div class="clearfix"></div>
        <?php $this->widget('EditableGallery', array(
                    'model'     => $item,
                    'attribute' => 'files',
                    'imageSize' => 'medium',
                    'imageAlt'  => $item->name,
                    'url'       => $fileUpdateUrl,
        			'apply'     => $editor,
        			'add'		=> !$tooLowPackage
        			//'add'		=> !$this->tooLowPackage
                    //'apply'     => $this->editorEnabled,
        			//'htmlOption' => array('style'=>'padding:20px')
        			//'class'     => 'thumbnail' 
                )); ?>
   		
   		</div>		
	</div><!-- div.right-frame -->
</div><!-- div.row -->
