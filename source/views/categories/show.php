<?php 
/**
 * Podstrona zawierająca listę elementów w kategorii.
 *
 * @category views
 * @package category
 * @author BAI
 * @copyright (C) 2014 BAI
 */
?>
<div class="row">
    <div class="span3">
        <?php if ($category) : ?>
            <?php if($this->beginCache('category-submenu-'.$category->id.'-'.Yii::app()->language.'-'.$search->getContext())) : ?>

                <?php $this->beginWidget(
                    'bootstrap.widgets.TbBox',
                        array(
                            'title' => false,
                            'htmlOptions' => array(
                                'class' => 'widget-box  menu-tree',
                            ),
                        )
                ); ?>
                    <h1><?php echo Yii::t('category.name', $category->alias, array($category->alias=>$category->name), 'dbMessages'); ?></h1>
                    <?php /*<h1><?php echo Yii::t('category.name', $category->name, array(), 'dbMessages'); ?></h1>*/?>

                    <hr />
					<?php //if($category->level < Yii::app()->params->categoryLevel) :?>
                    <?php $this->widget(
                        'bootstrap.widgets.TbMenu',
                        array(
                            'type' => 'list',
                            'items' => Category::getMenuItems($category->level+1, $category, false, false),
                            'encodeLabel' => false,
                            'htmlOptions' => array(
                                'class' => 'category-menu',
                            ),
                        )
                    ); //?>
                    <?php //endif; ?>
					
					<?php
                        $parent = $category->parent()->find();
                        if ($parent) {
                            echo Html::link(
                                '<i class="fa fa-level-up fa-flip-horizontal"></i> '
                                    . Yii::t('categories', 'go back to category above'),
//                                 $this->createUrl('categories/show', array(
								   $search->createUrl('categories/show', array(
                                    'name' => Yii::t('category.alias', $parent->alias, array(), 'dbMessages'))),
                                array('class' => 'category-up-link')
                            );
                        }
                    ?>
                    
                <?php $this->endWidget(); ?>

                <?php $this->endCache(); ?>
            <?php endif; ?>
        <?php else : ?>
            <?php if($this->beginCache('category-submenu--'.Yii::app()->language.'-'.$search->getContext())) : ?>

                <?php $this->beginWidget(
                    'bootstrap.widgets.TbBox',
                        array(
                            'title' => false,
                            'htmlOptions' => array(
                                'class' => 'widget-box  menu-tree',
                            ),
                        )
                ); ?>
                    <?php $this->widget(
                        'bootstrap.widgets.TbMenu',
                        array(
                            'type' => 'list',
                            'items' => Category::getMenuItems(1, null, false, false),
                            'encodeLabel' => false,
                            'htmlOptions' => array(
                                'class' => 'category-menu',
                            ),
                        )
                    ); ?>
                    
                <?php $this->endWidget(); ?>
                
                <?php $this->endCache(); ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <div class="span9">
        <?php $this->renderPartial('_list', compact('category', 'search', 'itemsDataProvider')); ?>
    </div>
</div>

