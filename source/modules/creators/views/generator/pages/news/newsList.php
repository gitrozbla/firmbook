<?php
    $listPage = max(abs(filter_var($tilde, FILTER_SANITIZE_NUMBER_INT)), 1);
    
    $this->widget('bootstrap.widgets.TbListView', array(
        'id' => 'news-list',
        'itemsTagName' => 'ul',
    	'dataProvider' => new CActiveDataProvider('News', array(
            'criteria' => array(
                'condition' => 'item_id = '.$page->website->company->item_id
                    .' AND active=1',
                'order' => 'date DESC',
            ),
            'countCriteria'=>array(
                'condition' => 'active=1',
            ),
            'pagination' => array(
                'class' => 'CreatorsPagination',
                'route' => $page->alias,
                'currentPage' => $listPage-1,
                'pageSize' => $page->items_per_page,
            ),
        )),
        'itemView' => 'pages/news/_newsListItem',
    	'template' => '{items}{pager}',
    	'enableSorting' => false,
        'emptyText' => Yii::t('CreatorsModule.generator', 'No news at this time.')
    ));
?>
