<?php 
/**
 * Mapa podkategorii.
 *
 * @category views
 * @package category
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php $categoryId = $category ? $category->id : 0; ?>
<?php if($this->beginCache('category-menu-page-'.$categoryId.'-'.Yii::app()->language.'-'.$search->getContext())) { ?>
    <?php if ($category) : ?>
        <h1><?php echo Yii::t('category.name', $category->name, array(), 'dbMessages'); ?></h1>
    <?php endif; ?>

    <hr />

    <ul class="menu-on-page">
        <?php foreach(Category::model()->getMenuItems($category ? 3 : 2, $category, false, false) as $subCategory) : ?>
            <li>
                <h2><?php echo Html::link($subCategory['label'], $subCategory['url']); ?></h2>
                <?php if (isset($subCategory['items'])) : ?>
                    <ul>
                        <?php foreach($subCategory['items'] as $subSubCategory) : ?>
                            <li>
                                <?php echo Html::link($subSubCategory['label'], $subSubCategory['url']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
            
    </ul>
<?php $this->endCache(); } ?>