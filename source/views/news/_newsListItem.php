<?php
/**
 * Aktualność na liście.
 *
 * @category views
 * @package news
 * @author
 * @copyright (C) 2015
 */
?>

<?php
	$url = $this->createGlobalRouteUrl('news/show', array(
    	'id'=>$data->id)); //Yii::t('item-'.$data->id, $data->alias, array(), 'dbMessages')));
	$updateUrl = $this->createGlobalRouteUrl('news/update', array('id'=>$data->id));
?>

<?php /*?><li class="<? $data->getPackageItemClass() ?>"><?php */?>
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
        <?php if($this->editorEnabled) : ?>
            <div class="pull-right clearfix">
                <?php $this->widget(
                    'bootstrap.widgets.TbButtonGroup',
                    array(
                        'size' => 'small',
                        'buttons' => array(
                            array(
                                'label' => Yii::t('editor', 'Edit'),
                                'url' => $updateUrl,
                                'icon' => 'fa fa-wrench',
                                ),
                            array(
                                'label' => Yii::t('editor', 'Remove'),
                                'url' => $this->createGlobalRouteUrl(
                                        '/news/remove',
                                        array('id'=>$data->id)
                                        ),
                                'type' => 'danger',
                                'icon' => 'fa fa-times',
                                'htmlOptions' => array(
                                    'class' => 'confirm-box',
                                    'data-message' => Yii::t(
                                            'news',
                                            '{remove-article?}',
                                            array(
                                                '{remove-article?}' =>
                                                'Are you sure you want to remove this article? '
                                                . 'You can also temporarily hide this article '
                                                . 'by clicking \'inactive\' in article edit form.'
                                                )),
                                )),
                        ),
                    )
                ); ?>
            </div>
        <?php endif; ?>
        <h2>
            <?php echo Html::link($data->title, $url); ?><br/>
            <?php if (!$data->active) :?>
                <span class="text-error">(<?php echo Yii::t('item', 'Inactive'); ?>)</span>
            <?php endif; ?>
        </h2>
        <small><?php echo Yii::app()->dateFormatter->format("yyyy-MM-dd", $data->date); ?></small>
        <p><?php echo $data->description ?></p>

    </div>
    <div class="inline column-contact">

    </div>
</li>

<?php /*<hr />*/ ?>
