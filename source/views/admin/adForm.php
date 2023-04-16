<?php 
/**
 * Formularz nowej reklamy.
 *
 * @category views
 * @package ads
 * @author
 * @copyright (C) 2015
 */ 
?>
<div class="row">
    <div class="span6">
        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',        	
            'htmlOptions' => array(
				'class' => 'well center', 
				'enctype' => 'multipart/form-data'
			),
        )); ?>
        <h1><?php
        	if($ad->getScenario() == 'insert')
        		echo 'Dodaj baner';
        	else 
        		echo 'Edytuj baner'; 
    					    
        ?></h1>
        
        <?php echo $form->dropDownListRow(
			$ad,
			'group_id',
			AdsBox::boxesToSelect()); ?>
        
        <?php echo $form->dropDownListRow(
			$ad,
			'type',
			array_combine(Ad::$_type, Ad::$_type)); ?>
			
        <?php echo $form->textFieldRow($ad, 'order_id'); ?>
        <?php //echo $form->textFieldRow($ad, 'group_id'); ?>        
        <?php echo $form->textFieldRow($ad, 'resource'); ?>
		<?php echo $form->fileFieldRow($ad, 'file_resource'); ?>
		<?php if ($ad->type == 'image' && !empty($ad['resource'])) : ?>
		<div class="control-group file-download">
			<div class="controls">
				<a href="<?php echo (Yii::app()->file->filesPath).'/Add/'.$ad->id.'/'.$ad['resource']; ?>" target="_blank">Download current image</a>
			</div>
		</div>
		<?php endif; ?>
        <?php echo $form->textAreaRow($ad, 'text'); ?>
        <?php //echo $form->textFieldRow($ad, 'text'); ?>
        <?php echo $form->textAreaRow($ad, 'text_css'); ?>
        <?php echo $form->textFieldRow($ad, 'alt'); ?>
        <?php echo $form->textFieldRow($ad, 'link'); ?>
        <?php echo $form->datePickerRow($ad, 'date_from'); ?>       
        <?php echo $form->datePickerRow($ad, 'date_to'); ?>
        <?php echo $form->checkboxRow($ad, 'no_limit'); ?>
        <?php echo $form->checkboxRow($ad, 'enabled'); ?>
         <hr />
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit', 
                'label' => Yii::t('packages', 'Save'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
                ))); ?>
            <?php $this->widget(
			                'bootstrap.widgets.TbButton',
			                array(
			                	'buttonType' => 'link',
			                	//'buttonType' => 'submit', 
			                    'label' => Yii::t('packages', 'Cancel'),
			                    'type' => 'primary',
			                    'url' => $this->createUrl('admin/ads'),
			                	
			                )
			            ); ?>    
        <?php $this->endWidget(); ?>
		
		
		<script>
			var select = $('#Ad_type');
			var resourceControlWrapper = $('#Ad_resource').closest('.control-group');
			var imageControlWrapper = $('#Ad_file_resource').closest('.control-group');
			var fileDownloadLink = $('.file-download');
			function changeResourceControl() {
				switch(select.val()) {
					case 'image':
					imageControlWrapper.show();
					fileDownloadLink.show();
					resourceControlWrapper.hide();
					break;
					
					case 'youtube':
					resourceControlWrapper.show();
					imageControlWrapper.hide();
					fileDownloadLink.hide();
					break;
				}
			}
			select.on('change', changeResourceControl);
			changeResourceControl();
		</script>
        
    </div>
</div>
