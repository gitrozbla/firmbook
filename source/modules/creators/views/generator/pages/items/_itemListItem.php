<?php 
    $model = $data;
    $item = $data->item;
    $url = $this->mapPage($this->page->alias.'/'.$item->id);
?>

<li>
    <div class="inline column-thumbnail">
        <?php if ($item->thumbnail != null) : ?>
            <?php echo Html::link(
                    Html::image($this->mapFile($item->thumbnail->generateUrl('medium'), 'item'), $item->name),
                    $url,
                    array(
                        'class' => 'thumbnail popover-trigger',
                        'data-content' => Html::image(
                                $this->mapFile($item->thumbnail->generateUrl('large'), 'item'), 
                                '')
                            .'<br />'.$item->name,
                        )); ?>
        <?php endif; ?>
    </div>
    <div class="inline column-title">
        <h3>
            <?php echo Html::link($item->name, $url); ?>
        </h3>
        <?php if($model->price): ?>
            <?php $this->widget('bootstrap.widgets.TbLabel',
                    array(
                        'type' => $model->promotion ? 'warning' : 'success',
                        'label' => $model->price.(isset($model->currency->name) ? ' '.$model->currency->name : '')
                    )); ?>			
        <?php endif;?>
    </div>
</li>

<?php /*<hr />*/ ?>