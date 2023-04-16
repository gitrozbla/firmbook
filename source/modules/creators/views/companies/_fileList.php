<h2><?php echo Yii::t('CreatorsModule.companies', 'Generated websites'); ?></h2>
<?php $this->widget(
    'GridView',
    array(
        'id' => 'grid-view',
        'type' => 'striped bordered',
        'dataProvider' => CreatorsFile::model()->dataProvider($company),
        'emptyText' => Yii::t('CreatorsModule.file', 'You have no generated files yet. Use buttons below to start.'),
        'columns' => array(
            //'id',
            array(
                'name' => Yii::t('CreatorsModule.file', 'File'),
                'value' => '$data->filename.".zip"',
            ),
            'generated',
            array(
                'template' => '{download}{delete}',
                'buttons' => array(
                    'download' => array(
                        'label' => Yii::t('CreatorsModule.file', 'Download'),
                        'icon' => 'fa fa-download',
                        'url' => '$data->generateUrl()',
                        'options' => array(
                            'target' => '_blank',
                        ),
                    ),
                    'delete' => array(
                        'icon' => 'fa fa-times',
                    )
                ),
		'class' => 'bootstrap.widgets.TbButtonColumn',
            ),
        ),
    )
);
