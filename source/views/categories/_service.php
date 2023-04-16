<?php 
/**
 * Usługa na liście.
 *
 * @category views
 * @package category
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php $url = $this->createUrl('services/show', array(
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
    <h2>
        <?php echo Html::link(
                !empty($data->name) 
                    ? Yii::t('item-'.$data->id, $data->name, array(), 'dbMessages')
                    : '('.Yii::t('item', 'No name').')',
                $url
            ); ?>
    </h2>
</div>
<div class="inline column-contact">
    
</div>

<hr />