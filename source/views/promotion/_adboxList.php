<?php 
/**
 * Oferta banerowa.
 *
 * @category views
 * @package promotion
 * @author
 * @copyright (C) 2014
 */ 
?>

<?php	
    $this->widget('bootstrap.widgets.TbListView', array(
        'id' => 'item-list',
    	'dataProvider' => AdsBox::model()->offerDataProvider(),
        //'dataProvider' => Item::model()->searchDataProvider($search, $category),
        'itemView' => '_adboxListItem',
        'itemsTagName' => 'ul',
    	//'viewData' => array('companyPage'=>1, 'elistItems' => $elistItems),
    	'template' => '{items}',
        'htmlOptions' => array(
            'class' => 'ad-list list-view',
        )
    ));
?>
