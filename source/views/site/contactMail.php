<?php 
/**
 * Wiadomość email dla formularza kontaktu.
 *
 * @category views
 * @package main
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<p>
    <?php echo Yii::t('contact', 'This message was sent using contact form on website'); ?> 
    <?php 
        $url = $this->createAbsoluteUrl(
                Yii::app()->controller->id.'/'.Yii::app()->controller->action->id
        );
        echo Html::link($url, $url, array('target'=>'_blank')); 
    ?>.<br />
</p>
<hr />
<p>
    <b><?php echo Yii::t('user', 'Contact information'); ?>:</b><br />
    <?php echo $model->forename; ?> <?php echo $model->surname; ?><br />
    <?php if (!empty($model->phone)) : ?>
        <?php echo Html::link(
                $model->phone, 
                'tel:'.str_replace(
                    array(' ', '-', '+', '(', ')'), 
                    '', 
                    $model->phone)
        ); ?><br />
    <?php endif; ?> 
    <?php if (!empty($model->email)) : ?>
        <?php echo Html::link(
                $model->email, 
                'mailto:'.$model->email
        ); ?><br />
    <?php endif; ?>
</p>
<hr />
<p>
    <b><?php echo Yii::t('contact', 'Message'); ?>:</b><br />
    <b style="font-style:italic;"><?php echo $model->subject; ?></b><br />
    <?php echo nl2br($model->message); ?><br />
</p>