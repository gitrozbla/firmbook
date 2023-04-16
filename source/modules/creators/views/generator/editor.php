<div class="alerts">
    <?php $this->widget('bootstrap.widgets.TbAlert'); ?>
</div>

<div class="editor-sidebar">
    <div class="editor-sidebar-wrapper">
        <div class="editor-sidebar-wrapper-2">
            <h1><?php echo Yii::t('CreatorsModule.editor', 'Creators editor'); ?></h1>

            <?php $this->renderPartial('form', compact('website')); ?>
        </div>
    </div>
    <a class="editor-sidebar-button" href="#"><?php echo Yii::t('CreatorsModule.editor', 'Creators editor'); ?></a>
</div>

<?php $previewUrl =  $this->createUrl('generator/preview', array(
    'id' => $website->company_id,
    't' => time()
)); ?>
<iframe id="preview" data-src="<?php echo $previewUrl; ?>"></iframe> 
<div class="preview-warning" style="display:none">
    <div><?php echo Yii::t('CreatorsModule.editor', 'Preview mode. Please remember to save changes!'); ?></div>
</div>

<?php $confirmQuestion = Yii::t('CreatorsModule.editor', 
        'Are you sure you want to close this window? There unsaved changes will be lost!') ?>
<a href="#" class="close-window" data-confirm="<?php echo $confirmQuestion; ?>">&times;</a>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->homeUrl.'js/creators/wysiwyg/'.Yii::app()->language.'.js', CClientScript::POS_END); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->homeUrl.'js/creators-editor.js', CClientScript::POS_END); ?>