<?php $form = $this->beginWidget(
    'ActiveForm',
    array(
        'id' => 'search-form',
        'type' => 'inline',
        'method' => 'GET',
   		'action' => $search->category !== null
	    	? $search->createUrl(
    				null,
    				array(
//     					'name'=>Yii::t(
//     						'category.alias',
//     						$search->category,
//     						array(),
//     						'dbMessages'),
    					'search_context'=>Yii::t('url','search'),
    					Search::getContextUrlType($search->type).'_context'=>Search::getContextUrlAction($search->action,$search->type)
    				)
    		)
    		: 
    		$search->createUrl('/categories/show', array('search_context'=>Yii::t('url','search'), Search::getContextUrlType($search->type).'_context'=>Search::getContextUrlAction($search->action,$search->type))),
        'actionPrefix' => '',
        'stateful' => false,
        'htmlOptions' => array(
            'class' => 'pull-right',
            ),
    )
); ?>
    
    <?php echo $form->textFieldRow(
            $search,
            'query',
            array(
                //'class' => 'input-large',
                'name' => Yii::t('url', 'query'),
                'placeholder' => Yii::t('search',
                    Search::getSearchLabel($search->action, $search->type)) . '...',
                'value' => $search->query,
                'class' => 'query-input search-simple-input'
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
    
    <?php $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'fa fa-search',
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
                    'type' => 'advanced',
                )),
                'htmlOptions' => array(
                    'class' => 'search-advanced-button search-expand cool-ajax',
                ),
            )
    ); ?>

    <div class="last-searches">
        <?php 
            $lastSearchesString = array();
            foreach ($search->findLastSearches() as $lastSearch) {
                $lastSearchesString []= Html::link(
                    $lastSearch->query,
                	$lastSearch->createUrl('/categories/show', array('search_context'=>Yii::t('url','search'), 'query'=>$lastSearch->query, Search::getContextUrlType($lastSearch->type).'_context'=>Search::getContextUrlAction($lastSearch->action,$lastSearch->type)))
//                     $lastSearch->createUrl('/categories/show')
                );
            }
            
        ?>
        <?php echo $this->widget('application.components.widgets.TextOverflowScroll', array(
                    'text' => implode(' | ', $lastSearchesString),
                    'animationType' => 'carousel',
                    'time' => 10000,
                    'delay' => 0,
                ), true); ?>
    </div>

<?php $this->endWidget(); ?>