<?php 
    // if controller does not inherit from Controller, 
    // there will be no $this->editorEnabled at all.
    if (isset($this->editorEnabled) 
            && $this->editorEnabled 
            && !Yii::app()->user->isGuest): ?>

        <?php $editor = Yii::app()->session['editor']; ?>

        <div class="editor-button pull-right">
        <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(
                    //'type' => 'primary',
                    'icon' => 'fa fa-wrench',
                    'label' => Yii::t('editor', $editor ? 'Finish editing' : 'Edit'),
                    'url' => $this->createUrl('/site/editor', array(
                        'return'=>urlencode(Yii::app()->request->requestUri),
                    )),
                    'active' => $editor,
                    'type' => 'success',
                    'htmlOptions' => array(
                        'data-placement' => 'bottom',
                        'data-content' => $editor 
                            ? Yii::t('editor', 'You can edit fields marked with dashed line.')
                            : Yii::t('editor', 'Click here to turn on edit mode.'),
                        'data-toggle' => 'popover',
                        'data-trigger' => 'hover',
                    ),
                )
        ); ?>
        <?php if ($editor) : ?>
            <br />
            <span class="page-editor-button-info">
                <?php echo Yii::t('editor', 'click to turn off'); ?>
            </span>
        <?php endif; ?>
    </div>
<?php endif; ?>