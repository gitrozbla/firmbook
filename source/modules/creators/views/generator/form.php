<?php
    $filesPath = 'files/CreatorsWebsite/'.$website->company_id.'/';
?>

<?php $form = $this->beginWidget('ActiveForm', array(
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
    'clientOptions'=>array(
        'afterValidate' => 'js:editorErrorHandler'
    )
)); ?>

    <fieldset>
        <legend>
            <a href="#">
                <?php echo Yii::t('CreatorsModule.editor', 'Metadata'); ?>
            </a>
        </legend>
        <div class="editor-block-content">
            <p class="quiet">
                <?php echo Yii::t('CreatorsModule.editor', 'This data is important for search engines and social networking.'); ?>
            </p>

            <?php echo $form->textFieldRow($website, 'meta_title'); ?>

            <?php echo $form->textAreaRow($website, 'meta_description'); ?>

            <?php echo $form->textAreaRow($website, 'meta_keywords'); ?>
            <p class="quiet">
                <?php echo Yii::t('CreatorsModule.editor', 'Please type in around 10 words related to offer, separated with comma.'); ?>
            </p>
        </div>
        <hr />
    </fieldset>

    <fieldset>
        <legend>
            <a href="#">
                <?php echo Yii::t('CreatorsModule.editor', 'Layout and appereance'); ?>
            </a>
        </legend>
        <div class="editor-block-content">
            <?php echo $form->dropDownListRow($website, 'layout', CreatorsWebsite::getLayouts(true)); ?>

            <?php echo $form->dropDownListRow($website, 'theme', CreatorsWebsite::getThemes(true)); ?>

            <?php echo $form->fileFieldRow($website, 'favicon', array(
                'data-value' => $website->favicon
                    ? $filesPath.$website->favicon
                    : '',
                'data-delete-attr' => 'CreatorsWebsite[faviconDelete]',
            )); ?>
            <p class="quiet small">
                <?php echo Yii::t('CreatorsModule.editor', 'This image will be shown on the top of browser tab, in favorites and shortcuts. '
                    . 'We suggest to upload image with square proportions and resolution 192x192 pixels.'); ?>
            </p>
        </div>
        <hr />
    </fieldset>

    <fieldset>
        <legend>
            <a href="#">
                <?php echo Yii::t('CreatorsModule.editor', 'Header'); ?>
            </a>
        </legend>
        <div class="editor-block-content">
            <?php echo $form->textFieldRow($website, 'name'); ?>

            <?php echo $form->colorpickerRow($website, 'name_color', array(
                'events'=>array(
                    'hide'=>'js:function(e){
                        $(this).trigger("change");
                    }')
            )); ?>

            <?php echo $form->fileFieldRow($website, 'logo', array(
                'data-value' => $website->logo
                    ? $filesPath.$website->logo
                    : '',
                'data-delete-attr' => 'CreatorsWebsite[logoDelete]',
            )); ?>

            <?php echo $form->textFieldRow($website, 'slogan'); ?>

            <?php echo $form->colorpickerRow($website, 'slogan_color', array(
                'events'=>array(
                    'hide'=>'js:function(e){
                        $(this).trigger("change");
                    }')
            )); ?>

            <?php echo $form->dropDownListRow($website, 'header_text_align', CreatorsWebsite::getTextAlign(true)); ?>

            <?php echo $form->fileFieldRow($website, 'header_bg', array(
                'data-value' => $website->header_bg
                    ? $filesPath.$website->header_bg
                    : '',
                'data-delete-attr' => 'CreatorsWebsite[header_bgDelete]',
            )); ?>

            <?php echo $form->checkBoxRow($website, 'extended_header_bg'); ?>

            <?php echo $form->rangeFieldRow($website, 'header_bg_brightness', array(
                'min' => '-1.0',
                'max' => '1.0',
                'step' => '0.05',
                'data-precision' => '2',
            )); ?>
            <div class="output" data-for="CreatorsWebsite_header_bg_brightness"></div>

            <?php echo $form->rangeFieldRow($website, 'header_height', array(
                'min' => '10',
                'max' => '600',
                'step' => '5',
                'data-precision' => '0',
            )); ?>
            <div class="output" data-for="CreatorsWebsite_header_height"></div>

            <?php echo $form->checkBoxRow($website, 'header_heightAuto'); ?>

						<?php echo $form->checkBoxRow($website, 'header_social_icons'); ?>
						<p class="quiet">
								<?php echo Yii::t('CreatorsModule.editor', 'You can edit appearance of this button in "Social icons" section.'); ?>
						</p>
        </div>
        <hr />
    </fieldset>

    <fieldset>
        <legend>
            <a href="#">
                <?php echo Yii::t('CreatorsModule.editor', 'Pages'); ?>
            </a>
        </legend>
        <div class="editor-block-content">

            <?php echo $form->hiddenField($website, 'home_page_id',
                                array('class'=>'CreatorsWebsite_home_page_id')); ?>

            <ul>
                <?php $count = count($website->pages); ?>
                <?php foreach($website->pages as $index=>$page) : ?>
                <li data-page-id="<?php echo $page->id; ?>">
                    <div class="editor-page-title">
                        <div class="editor-page-title-options pull-right <?php echo $page->scenario == 'empty' ? 'no-display' : ''; ?>">
                            <a href="#" class="editor-page-home <?php
                                    echo ($website->home_page_id!=null && $website->home_page_id==$page->id)
                                        || ($website->home_page_id==null && $index==0) ? '':'faded';
                            ?>">
                                <i class="fa fa-home"></i>
                            </a>
                            <a href="#" class="editor-page-move-down <?php echo $index<$count-2 ? '':'faded'; ?>">
                                <i class="fa fa-arrow-down"></i>
                            </a>
                            <a href="#" class="editor-page-move-up <?php echo $index>0 ? '':'faded'; ?>">
                                <i class="fa fa-arrow-up"></i>
                            </a>
                            <a href="#" class="editor-page-remove"
                                    data-message="<?php echo Yii::t('CreatorsModule.editor', 'Remove page?');?>">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        <a href="#" class="editor-page-title-link">
                            <?php if ($page->title) {
                                echo Html::encode($page->title);
                            } else if ($page->scenario == 'empty') {
                                echo '<i class="faded">['.Yii::t('CreatorsModule.editor', 'type title to add page').']</i>';
                            } else {
                                echo '<i class="faded">['.Yii::t('CreatorsModule.page', 'no name').']</i>';
                            } ?>
                        </a>

                        <?php echo $form->hiddenField($page, '['.$index.']id',
                                array('class'=>'CreatorsPage_id')); ?>
                        <?php echo $form->hiddenField($page, '['.$index.']position',
                                array('class'=>'CreatorsPage_position')); ?>
                        <?php echo $form->hiddenField($page, '['.$index.']scenario',
                                array('class'=>'CreatorsPage_scenario')); ?>
                    </div>
                    <div class="editor-page-content">
                        <?php echo $form->textFieldRow($page, '['.$index.']title',
                                array(
                                    'class' => 'CreatorsPage_title',
                                    'data-add-message' => Yii::t('CreatorsModule.editor', 'type title to add page'),
                                    'data-no-name-message' => Yii::t('CreatorsModule.page', 'no name')
                                    )); ?>

                        <?php echo $form->textFieldRow($page, '['.$index.']alias',
                                array('class'=>'CreatorsPage_alias')); ?>

                        <?php echo $form->dropDownListRow($page, '['.$index.']type',
                                CreatorsPage::getTypes(true),
                                array(
                                    'class' => 'CreatorsPage_type',
                                    'data-message' => Yii::t('CreatorsModule.editor',
                                            'The rest part of this page will be generated after saving changes.')
                                    )); ?>
                        <div>
                            <?php echo $form->textFieldRow($page, '['.$index.']items_per_page',
                                    array(
                                        'class' => 'CreatorsPage_items_per_page',
                                        'data-message' => Yii::t('CreatorsModule.editor',
                                            'The rest part of this page will be generated after saving changes.')
                                        )); ?>
                        </div>
												<div>
                            <?php echo $form->checkBoxRow($page, '['.$index.']company_data',
                                    array(
                                        'class' => 'CreatorsPage_company_data',
                                        'data-message' => Yii::t('CreatorsModule.editor',
                                            'The rest part of this page will be generated after saving changes.')
                                        )); ?>
                        </div>

                        <?php echo $form->html5EditorRow($page, '['.$index.']content',
                                array(
                                    'editorOptions' => array(
                                        'locale' => Yii::app()->language,
                                        'color' => true,
                                        'html' => true,
                                    ),
                                    'htmlOptions'=>array(
                                        'class' => 'CreatorsPage_content',
                                        'data-removed-message' => Yii::t('CreatorsModule.page',
                                                'this page has been removed')
                                        )
                                )); ?>
								
						<div>
                            <?php echo $form->checkBoxRow($page, '['.$index.']comments',
                                array('class' => 'CreatorsPage_comments')
                            ); ?>
                        </div>
						<div>
                            <?php echo $form->checkBoxRow($page, '['.$index.']comments_from_firmbook',
                                array('class' => 'CreatorsPage_comments_from_firmbook')
                            ); ?>
                        </div>
						<div class="CreatorsPage_buttons">
							<?php echo $form->checkBoxListRow($page, '['.$index.']buttons', CreatorsPage::getAllButtons()); ?>
							<p class="quiet">
								<?php echo Yii::t('CreatorsModule.editor', 'Buttons are visible only when prize or delivery details are provided.'); ?>
							</p>
						</div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>

            <p class="editor-add-page-message no-display quiet">
                <?php echo Yii::t('CreatorsModule.editor', 'Please save changes before adding more pages.'); ?>
            </p>
        </div>
        <hr />
    </fieldset>

    <fieldset>
        <legend>
            <a href="#">
                <?php echo Yii::t('CreatorsModule.editor', 'Footer'); ?>
            </a>
        </legend>
        <div class="editor-block-content">
            <?php echo $form->html5EditorRow($website, 'footer_text', array(
                'editorOptions' => array(
                    'locale' => Yii::app()->language,
                    'color' => true,
                    'html' => true,
                )
            )); ?>

						<?php echo $form->checkBoxRow($website, 'footer_social_icons'); ?>
						<p class="quiet">
								<?php echo Yii::t('CreatorsModule.editor', 'You can edit appearance of this button in "Social icons" section.'); ?>
						</p>
        </div>
        <hr />
    </fieldset>

		<fieldset>
        <legend>
            <a href="#">
                <?php echo Yii::t('CreatorsModule.editor', 'Social icons'); ?>
            </a>
        </legend>
        <div class="editor-block-content">
						<p class="quiet">
								<?php echo Yii::t('CreatorsModule.editor', 'Change visibility of this button in header or footer section.'); ?>
						</p>
            <?php echo $form->textFieldRow($website, 'social_icons_title', array(
							'placeholder' => '('.Yii::t('CreatorsModule.editor', 'default').')'
						)); ?>

						<?php echo $form->checkBoxListRow($website, 'social_icons_networks', CreatorsWebsite::getAllSocialNetworks()); ?>
				</div>
        <hr />
    </fieldset>

    <div class="alert alert-block alert-error editor-error-summary no-display">
        <p><?php echo Yii::t('yii','Please fix the following input errors:'); ?></p>
        <ul><li></li></ul>
    </div>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'label' => Yii::t('CreatorsModule.editor', 'Save'),
        'type' => 'primary'
        )); ?>

    <?php echo Html::link(
            Yii::t('CreatorsModule.editor', 'or cancel changes'), '#',
            array(
                'class' => 'margin-10 editor-cancel no-display',
                'data-confirm' => Yii::t('CreatorsModule.editor',
                        'Are you sure you want to cancel all changes?'))); ?>

    <div class="clearfix"></div>

<?php $this->endWidget();   // form ?>
