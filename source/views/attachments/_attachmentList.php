<?php
$creatorsGenerator = (Yii::app()->params['websiteMode'] == 'creators' && $this->id == 'generator')
	? true : false;

$this->widget(
    'GridView',
    array(
        'id' => 'grid-view',
        'type' => 'striped bordered',
        'dataProvider' => Attachment::model()->dataProvider($item->id),
    	'enableSorting' => false,
        'columns' => array(
        	array(
        		'name' => Yii::t('attachment', 'Name'),
        		'value' => '$data->anchor',
        		'htmlOptions' => array(
        			'style' => $item->cache_type=='c' ? 'width: 35%' : 'width: 50%'
        		)
        	),
            /*array(
                'name' => Yii::t('attachment', 'Name'),
            	'value' => '$data->anchor'
            ),*/
        	array(
        		'name' => Yii::t('attachment', 'Size'),
        		'value' => '$data->formatedFileSize()'
        	),
            'date',
						array(
                'template' => '{download}',
                'buttons' => array(
                    'download' => array(
                    	'label' => Yii::t('attachment', 'Download'),
                        'icon' => 'fa fa-download',
                        'url' => $creatorsGenerator
							? 'Yii::app()->controller->mapFile($data->generateUrl(), "attachments")'
							: '$data->generateUrl()',
                        'options' => array(
                            'target' => '_blank',
                        ),
                    	//'visible' => $item->user_id == Yii::app()->user->id ? 'true' : 'false'
                    ),
                ),
				'class' => 'bootstrap.widgets.TbButtonColumn'
            ),
            array(
                'template' => '{update} {delete}',
                'buttons' => array(
                	'update' => array(
                		'url' => 'Yii::app()->controller->createGlobalRouteUrl('
                			. '"attachments/update/id/".$data->id)',
                		'icon' => 'fa fa-pencil',
                		//'visible' => $item->user_id == Yii::app()->user->id ? 'true' : 'false'
                	),
                    'delete' => array(
                    	'url' => 'Yii::app()->controller->createGlobalRouteUrl('
                            . '"attachments/remove/id/".$data->id)',
                        'icon' => 'fa fa-times',
                        //'visible' => $item->user_id == Yii::app()->user->id ? 'true' : 'false'
                    ),
                ),
				'class' => 'bootstrap.widgets.TbButtonColumn',
            	'visible' => $item->user_id == Yii::app()->user->id
							 	&& ($creatorsGenerator == false)? true : false
            ),
        ),
    )
);
