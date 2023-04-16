<?php 
/**
 * Produkt na liście.
 *
 * @category views
 * @package category
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php $url = $this->createUrl('products/show', array(
    'name'=>Yii::t('item-'.$data->id, $data->alias, array(), 'dbMessages'))); ?>

<div class="inline column-thumbnail">
    <?php if ($data->thumbnail != null) : ?>
        <?php echo Html::link(
                Html::image($data->thumbnail->generateUrl('s'), $data->name),
                $url,
                array(
                    'class' => 'thumbnail',
                    'data-content' => Html::image(
                            $data->thumbnail->generateUrl('l'), 
                            '',
                            array('style'=>'height: 200px;')),
                    )); ?>
    <?php endif; ?>
</div>
<div class="inline column-title">
    <?php if (Yii::app()->session['editor'] && $data->user_id == Yii::app()->user->id) : ?>
        <div class="pull-right">
            <?php $this->widget(
                'bootstrap.widgets.TbButtonGroup',
                array(
                    'size' => 'small',
                    'buttons' => array(
                        array(
                            'label' => Yii::t('editor', 'Edit'),
                            'url' => $url,
                            'icon' => 'wrench',
                            ),
                        array(
                            'label' => Yii::t('editor', 'Remove'),
                            'url' => $this->createUrl(
                                    '/categories/remove_item',
                                    array('id'=>$data->id)
                                    ),
                            'type' => 'danger',
                            'icon' => 'remove',
                            'htmlOptions' => array(
                                'class' => 'confirm-box',
                                'data-message' => Yii::t(
                                        'editor', 
                                        '{remove-product?}', 
                                        array(
                                            '{remove-product?}' => 
                                            'Are you sure you want to remove this product? '
                                            . 'You can also temporarily hide this offer '
                                            . 'by clicking \'inactive\' on products page.'
                                            )),
                            )),
                    ),
                )
            ); ?>
        </div>
    <?php endif; ?>
    <h2>
        <?php echo Html::link(
                !empty($data->name) 
                    ? Yii::t('item-'.$data->id, $data->name, array(), 'dbMessages')
                    : '('.Yii::t('item', 'No name').')',
                $url
            ); ?><br/>
        <?php if (!$data->active) :?>
            <span class="text-error">(<?php echo Yii::t('item', 'Inactive'); ?>)</span>
        <?php endif; ?>
    </h2>
</div>
<div class="inline column-contact">
    
</div>

<hr />