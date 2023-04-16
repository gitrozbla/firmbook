<?php 
/**
 * Wiadomość email po dodaniu do obserwowanych.
 * @category views
 * @package main
 * @author 
 * @copyright 
 */ 
?>
<?php
    $search = Search::model()->getFromSession();    
    $search->type = $item->typeName();
    $search->action = $item->sell ? 'sell' : 'buy';
    $categoryUrl = $search->createUrl('categories/show', array('name'=>Yii::t('category.alias', $item->category->alias, null, 'dbMessages')), false, true);
    $categoryName = Yii::t('category.name', $item->category->alias, array($item->category->alias=>$item->category->name), 'dbMessages', $recipient['language']);
    $categoryLink = '<b>'.Html::link($categoryName, $categoryUrl, array('target'=>'_blank')).'</b>';
    $itemLink = '<b>'.Html::link($item->name, $item->url(true), array('target'=>'_blank')).'</b>'; 
    
    switch($item->cache_type) {
        case 'p':
            $message = Yii::t('category', 'A new product {item_name} in the category {category_name}.', array('{item_name}' => $itemLink, '{category_name}' => $categoryLink), null, $recipient['language']);
            break;
        case 's':
            $message = Yii::t('category', 'A new service {item_name} in the category {category_name}.', array('{item_name}' => $itemLink, '{category_name}' => $categoryLink), null, $recipient['language']);
            break;
        default:
            $message = Yii::t('category', 'A new company {item_name} in the category {category_name}.', array('{item_name}' => $itemLink, '{category_name}' => $categoryLink), null, $recipient['language']);
    }
?>
<p>
    <?php // echo Yii::t('elists', 'Firmbook System Poland informs', [], null, $recipient['language']); ?> 
    <?php echo Yii::t('site', 'FIRMBOOK SYSTEM POLAND informs', [], null, $recipient['language']); ?>
</p>
<hr />
<p>
    <?php 
        echo $message;
    ?>
</p>