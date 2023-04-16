<?php 
/**
 * Lista elementów.
 *
 * @category views
 * @package category
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php Yii::app()->clientScript->registerScript(
        'item-list-thumbnail-hover',
        "$('#item-list .thumbnail').popover({"
            . "'trigger': 'hover',"
            . "'html': true"
        . "});"
        ); ?>

<?php 
    $type = $search->type;
    $class = ucfirst($type);
    switch($type) {
        case 'product':
            $view = '/products/_productListItem';
            $controller = 'products';
            break;
        case 'service':
            $view = '/services/_serviceListItem';
            $controller = 'services';
            break;
        case 'company':
            $view = '/companies/_companyListItem';
            $controller = 'companies';
            break;
    }
?>

<?php if (Yii::app()->user->checkAccess(ucfirst($controller).'.add')) : ?>
    <?php $addButtonDisabled = empty(Search::model()->getFromSession()->category); ?>
    <?php $this->widget(
        'Button',
        array(
            'label' => Yii::t($type, 'Add new '.$type),
            'type' => 'success',
            'icon' => 'fa fa-plus',
            'url' => $this->createUrl($controller.'/add'),
            'disabled' => $addButtonDisabled,
        )
    ); ?>
    <?php if ($addButtonDisabled) : ?>
        <span class="muted">
            <?php echo Yii::t('categories', 'First select a category.'); ?>
        </span>
    <?php endif; ?>
            
<?php endif; ?>

<?php
    $this->widget('bootstrap.widgets.TbListView', array(
        'id' => 'item-list',
        'dataProvider' => Item::model()->searchDataProvider($search, $category),
        'itemView' => $view,
        'itemsTagName' => 'ul',
        'htmlOptions' => array(
            'class' => 'big-list list-view',
        )
    ));
?>