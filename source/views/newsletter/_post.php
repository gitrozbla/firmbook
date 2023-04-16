<?php 
/**
 * Post na liście.
 *
 * @category views
 * @package newsletter
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php if (!$data->sent) : ?>
    <div class="dashed">
        <div class="pull-right">
            <?php echo Html::link(
                    Yii::t('newsletter', 'Send'), 
                    $this->createUrl('newsletter/send', array('id'=>$data->id)),
                    array(
                        'class' => 'btn btn-primary',
                    )
                    ); ?>
        </div>

        <h2><?php $this->widget('EditableField', array(
            'model'     => $data,
            'attribute' => 'subject',
            'url'       => $this->createUrl('newsletter/update_post'),
        )); ?></h2>

        <div class="clearfix"></div>
        <p><?php $this->widget('EditableEditor', array(
            'model'     => $data,
            'attribute' => 'content',
            'url'       => $this->createUrl('newsletter/update_post'),
            'emptytext' => Yii::t('newsletter', 'Type content'),
        )); ?></p>
        
        <div class="pull-right">
            <?php echo Html::coolAjaxLink(
                    '<i class="fa fa-trash-o"></i>&nbsp;'.Yii::t('newsletter', 'Remove from list'), 
                    $this->createUrl('newsletter/remove_post', array('id'=>$data->id)),
                    array(
                        'class' => 'confirm-box',
                        'data-message' => Yii::t('newsletter', 'This post wasn\'t sent yet! Remove it?')
                        )
                    ); ?>
        </div>
        
        <div class="clearfix"></div>
    </div>
<?php else: ?>
    <div>
        <div class="pull-right">
            <?php echo Yii::t('newsletter', 'Sent'); ?>
            <?php $this->widget('EditableField'/*'EditableDatetime'*/, array(
                'model'     => $data,
                'type'      => 'datetime',
                'attribute' => 'datetime',
                //'dateSourceFormat' => 'unix_timestamp',
                'url'       => $this->createUrl('test/update'),
            )); ?><br />
        </div>

        <h2><?php echo $data->subject; ?></h2>

        <div class="clearfix"></div>

        <p><?php echo $data->content; ?></p>
        <div class="pull-right">
            <?php echo Html::coolAjaxLink(
                    '<i class="fa fa-trash-o"></i>&nbsp;'.Yii::t('newsletter', 'Remove from list'), 
                    $this->createUrl('newsletter/remove_post', array('id'=>$data->id)),
                    array(
                        'class' => 'confirm-box',
                        'data-message' => Yii::t('newsletter', 'Remove this post from archive?')
                        )
                    ); ?>
        </div>
        
        <div class="clearfix"></div>
    </div>
<?php endif; ?>
<hr />
