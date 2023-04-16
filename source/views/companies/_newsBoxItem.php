<?php
/**
 * Aktualność w boxie.
 *
 * @category views
 * @package company
 * @author
 * @copyright (C) 2015
 */
?>

<?php
	$url = $this->createGlobalRouteUrl('news/show', array(
    	'id'=>$data->id)); //Yii::t('item-'.$data->id, $data->alias, array(), 'dbMessages')));
	//$updateUrl = $this->createUrl('news/update', array('id'=>$data->id));
?>

<li>
	<div class="inline column-thumbnail">
			<?php if ($data->photo != null) : ?>
					<?php echo Html::link(
									Html::image($data->photo->generateUrl('small'), $data->title),
									$url,
									array(
											'class' => 'thumbnail',
											'data-content' => Html::image(
															$data->photo->generateUrl('large'),
															'',
															array('style'=>'height: 200px;'))
													.'<br />'.$data->title,
											)); ?>
			<?php endif; ?>
	</div>

    	<div class="inline column-title">
        <h2>
            <?php echo Html::link($data->title, $url); ?><br/>
            <?php if (!$data->active) :?>
                <span class="text-error">(<?php echo Yii::t('item', 'Inactive'); ?>)</span>
            <?php endif; ?>
        </h2>
    </div>
    <div class="inline column-contact">

    </div>
</li>
