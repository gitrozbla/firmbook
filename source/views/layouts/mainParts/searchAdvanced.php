<?php $form = $this->beginWidget(
    'ActiveForm',
    array(
        'id' => 'search-form',
        //'type' => 'inline',
        'method' => 'GET',
        'action' => $search->category !== null
            ? $this->createUrl(
                    '/categories/show', 
                    array('name'=>Yii::t(
                            'category.alias', 
                            $search->category, 
                            array(), 
                            'dbMessages'))
                    )
            : $this->createUrl('/categories/show'),
        'actionPrefix' => '',
        'stateful' => false,
    )
); ?>

    <?php echo $form->hiddenField(
        $search,
        'context',
        array(
            'name' => Yii::t('url', 'context'),
            'value'=>Yii::t('context', $search->context),
        )
    ); ?>
    <?php if (!empty($search->category)) : ?>
        <?php echo $form->hiddenField(
            $search,
            'category',
            array(
                'name' => Yii::t('url', 'category'),
                'value' => $search->category
                    ? Yii::t('category.alias', 
                            $search->category, 
                            array(),
                            'dbMessages')
                    : ''
            )
        ); ?>
    <?php endif; ?>
    <div class="well">
        <div class="row-fluid">

            <div class="span4">
                
                <?php echo $form->textFieldRow(
                    $search,
                    'query',
                    array(
                        //'class' => 'input-large',
                        'name' => Yii::t('url', 'query'),
                        'value' => $search->query,
                        'class' => 'query-input',
                    ),
                    array(
                        'append' => Html::link(
                            '<i class="fa fa-times"></i>', 
                            '#',
                            array(
                                'class' => 'clear-field',
                            )),
                    )
                ); ?>
            </div>

            <div class="span4">
                <?php echo $form->textFieldRow(
                        $search,
                        'username',
                        array(
                            //'class' => 'input-large',
                            'name' => Yii::t('url', 'username'),
                            'value' => $search->username,
                        ),
                        array(
                            'append' => Html::link(
                                    '<i class="fa fa-times"></i>', 
                                    '#',
                                    array(
                                        'class' => 'clear-field',
                                    )),
                        )
                ); ?>
            </div>

            <div class="span4 padding-top-25 text-center">
                <?php $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                            'buttonType' => 'submit',
                            'type' => 'primary',
                            'icon' => 'fa fa-search',
                            'label' => Yii::t('search',
                                Search::getSearchLabel($search->action, $search->type)) . '...',
                            'htmlOptions' => array(
                                'name' => Yii::t('url', 'search'),
                                'value' => '',
                            ),
                        )
                ); ?>
                <?php $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                            'type' => 'primary',
                            'label' => '...',
                            'url' => $this->createUrl('/categories/search_form', array(
                                'type' => 'simple',
                            )),
                            'htmlOptions' => array(
                                'class' => 'search-advanced-button search-collapse cool-ajax',
                            ),
                        )
                ); ?>
            </div>

        </div>
    </div>
<?php $this->endWidget(); ?>