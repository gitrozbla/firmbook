<?php
    $listPage = max(abs(filter_var($tilde, FILTER_SANITIZE_NUMBER_INT)), 1);
    
    $packageItemsLimit = null;
    
    //$this->sideContent = 'test';
    
    $condition = 't.company_id='.$this->website->company_id.' AND item.active=1';
    
    $this->widget('bootstrap.widgets.TbListView', array(
        'id' => $type.'-list',
        'itemsTagName' => 'ul',
    	'dataProvider' => new CActiveDataProvider($modelName, array(
            'criteria' => array(
                'order' => 'item.date DESC',
                'with' => 'item',
                'condition' => $condition,
            ),
            'countCriteria'=>array(
                'with' => 'item',
                'condition' => $condition,
            ),
            'pagination' => array(
                'class' => 'CreatorsPagination',
                'route' => $page->alias,
                'currentPage' => $listPage-1,
                'pageSize' => $page->items_per_page,
                'limit' => $packageItemsLimit,
            ),
            'totalItemCount' => $packageItemsLimit,
        )),
        'itemView' => 'pages/items/_itemListItem',
    	'template' => '{items}{pager}',
    	'enableSorting' => false,
        'emptyText' => Yii::t('CreatorsModule.generator', 'Currently '.$type.' list is empty')
    ));
?>
