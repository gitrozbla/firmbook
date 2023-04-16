<?php $this->widget(
    'GridView',
    array(
        'id' => 'grid-view',
        'type' => 'striped bordered',
        'dataProvider' => Item::model()->searchDataProvider($search),
        //'filter' => $user,
        'rowCssClassExpression' => '$data->active ? "" : "fade-medium";',
        'columns' => array(
//             'id',
            array(
                'id' => 'thumbnail',
                'value' => array($this, 'renderThumbnail'),
                'type' => 'raw',
            ),
            array(
                'name' => 'name',
                'value' => 'Html::link(
                    $data->name,
                    Yii::app()->controller->createUrl('
                            . '"companies/show",array("name"=>$data->alias))
                )',
                'type' => 'raw',
            ),
            array(
                'name' => 'active',
                'value' => '"<i class=\"fa fa-".($data->active ? "check-" : "")."square-o\"></i>"',
                'type' => 'raw',
            ),
            array(
                'template' => '{configure}{edit}',
                'buttons' => array(
                    'configure' => array(
                        'label' => Yii::t('CreatorsModule.companies', 'Configure Website'),
                        'icon' => 'fa fa-magic',
                        'url' => 'Yii::app()->controller->createUrl('
                            . '"companies/show",array("name"=>$data->alias))',
                    ),
                    'edit' => array(
                        'label' => Yii::t('CreatorsModule.companies', 'Edit company data'),
                        'icon' => 'fa fa-pencil-square-o',
                        'url' => 'Yii::app()->controller->createGlobalRouteUrl('
                            . '"companies/show",array("name"=>$data->alias))',
                    ),
                ),
		'class' => 'bootstrap.widgets.TbButtonColumn',
            ),
        ),
    )
);
