<?php
/**
 * Formularz - dodanie i edycja artykułu.
 *
 * @category views
 * @package accoewsunt
 * @author
 * @copyright (C) 2015
 */


?>
<?php
    //$class = get_class($product);
    //$source = strtolower($class);
?>
<div class="row">
	<?php //require './source/views/companies/_companyPanel.php'; ?>
    <div class="span8 well">
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',
        	'htmlOptions' => array(
						'enctype' => 'multipart/form-data',
						'class' => 'center'
					),
        )); ?>
        <h1><?php
        	if($news->getScenario() == 'create')
        		echo Yii::t('news', 'Add new article');
        	else
        		echo Yii::t('news', 'Edit article data');
        ?></h1>

        <fieldset>
        <legend><?php echo Yii::t('news', 'Article data'); ?>:</legend>
        <?php echo $form->checkBoxRow($news, 'active',
        	array('0'=> Yii::t('item', 'Inactive'), '1'=> Yii::t('item', 'Active')),
        	array('labelOptions'=> array(
                 'style' => "display: inline-block"
           	))); ?>
        <?php echo $form->textFieldRow($news, 'title'); ?>



        <?php echo $form->textAreaRow($news, 'description'); ?>

				<?php //echo $form->fileFieldRow($news, 'photo'); ?>
				<script>
						function newsThumbnailSelectionFormat(state) {
						    //if (!state.id) return state.text; // optgroup
								if (state.id == 'none' || state.id == 'other') return state.text;
						    return "<img class=\'news-thumbnail-selection\' src=\'" + state.text + "\'/>";
						}
				</script>
				<?php
					$selectData = (
						array('none' => Yii::t('news', 'none'))
						+ $news->getAllowedPhotos()
						+ array('other' => Yii::t('news', 'other').'...')
					);
					echo $form->select2Row($news, 'photo_file_id', array(
						'val' => isset($selectData[$news->photo_file_id]) ? $news->photo_file_id : '',
						'data' => $selectData,
						'options' => array(
							'formatSelection' => "js:newsThumbnailSelectionFormat",
							'formatResult' => "js:newsThumbnailSelectionFormat",
							'containerCssClass' => 'news-select-photo',
							//escapeMarkup: function(m) { return m; }
						)
					)); ?>
				<?php echo $form->fileFieldRow($news, 'photoUpload'); ?>
				<script>
					jQuery(function($) {
						var select = $('#News_photo_file_id');
						var input = $('#News_photoUpload').closest('.control-group');
						var changeFunction = function() {
							if (select.val() == 'other') input.fadeIn();
							else input.hide();
						}
						changeFunction();
						select.on('change', changeFunction);
					});
				</script>

        <?php //echo $form->html5EditorRow($news, 'content'); ?>
        <?php echo $form->ckEditorRow($news, 'content'); ?>
        <?php //echo $form->ckEditorRow($news, 'content'); ?>
        <?php //echo $form->redactorRow($news, 'content'); ?>
        <?php //echo $form->html5EditorRow($news, 'content'); ?>

        <?php //echo $form->html5EditorRow($news, 'content'); ?>
        <?php /*echo $form->html5EditorRow($news, 'content', array(
'editorOptions' => array(
'class' => 'span4',
'rows' => 3,
'height' => '200',
'options' => array('color' => true)
)
));*/ ?>


        <hr />
        </fieldset>

		<div class="form-actions">
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
			                    'url' => Yii::app()->request->urlReferrer,
			                )
			            ); ?>

		</div>
        <?php $this->endWidget(); ?>

    </div>
    <div class="span3 pull-right">
        <?php if ($company) : ?>
            <?php $this->renderPartial('/companies/_companyBox', compact('company')); ?>
        <?php endif; ?>
    </div>
</div>
