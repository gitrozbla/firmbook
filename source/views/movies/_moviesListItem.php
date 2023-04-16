<?php 
/**
 * Film na liście filmów.
 *
 * @category views
 * @package company
 * @author
 * @copyright (C) 2015
 */ 
?>
<li class="span3" style="margin-left:0; margin-left:10px; height: <?php if($this->editorEnabled) : ?>300<?php else: ?>250<?php endif;?>px">
	<div class="thumbnail" style="height: <?php if($this->editorEnabled) : ?>280<?php else: ?>230<?php endif;?>px">
		<?php if($this->editorEnabled) : ?>
            	<div class="text-center">
                <?php $this->widget(
                    'bootstrap.widgets.TbButtonGroup',
                    array(
                        'size' => 'small',
                        'buttons' => array(
                            array(
                                'label' => Yii::t('editor', 'Edit'),
                                'url' => $this->createGlobalRouteUrl('movies/update', array('id'=>$data->id)),
                                'icon' => 'fa fa-wrench',
                                ),
                            array(
                                'label' => Yii::t('editor', 'Remove'),
                                'url' => $this->createGlobalRouteUrl(
                                        '/movies/remove',
                                        array('id'=>$data->id)
                                        ),
                                'type' => 'danger',
                                'icon' => 'fa fa-times',
                                'htmlOptions' => array(
                                    'class' => 'confirm-box',
                                    'data-message' => Yii::t(
                                            'movies', 
                                            '{remove-movie?}', 
                                            array(
                                                '{remove-movie?}' => 'Are you sure you want to remove this movie?'
                                                )),
                                )),
                        ),
                    )
                ); ?>
            	</div>
            	<br />
        	<?php endif; ?>
		<?php echo Html::link(CHtml::image('http://img.youtube.com/vi/'.$data->url.'/0.jpg', ''), $this->createGlobalRouteUrl('movies/show', array('id'=>$data->id))); ?>
		<div class="caption">
            <?php if($data->title): ?><h5><?php echo $data->title ?></h5><?php endif; ?>
            <?php /*if($data->description): ?><p><?php echo $data->description ?></p><?php endif;*/ ?>            
        </div>
	</div>
</li>
<?php /*<li class="span3">
	<a href="#" class="thumbnail" rel="tooltip" data-title="Tooltip">
		<?php echo CHtml::image('http://img.youtube.com/vi/'.$data->url.'/0.jpg', ''); ?>
	</a>
</li> */?>

<?php /*<div class="col-sm-6 col-md-4">
    <div class="thumbnail">
        <?php echo CHtml::image('http://img.youtube.com/vi/'.$data->url.'/0.jpg', ''); ?>
        <div class="caption">
            <h3>Thumbnail label</h3>
            <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
            <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
        </div>
    </div>
</div> */?>

