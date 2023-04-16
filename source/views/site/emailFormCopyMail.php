<?php 
/**
 * Wiadomość email dla formularza kontaktu.
 *
 * @category views
 * @package main
 * @author
 * @copyright (C)
 */ 
?>
<p>
    <?php echo Yii::t('contact', 'This message was sent using contact form on website'); ?> 
    <?php 
        /*$url = $this->createAbsoluteUrl(
                Yii::app()->controller->id.'/'.Yii::app()->controller->action->id
        );*/
        $url = Yii::app()->request->urlReferrer;
        echo Html::link($url, $url, array('target'=>'_blank')); 
    ?>
    <?php if(isset($recipientObj)) : ?>
        <br />
        <?php echo Yii::t('contact', 'To'); ?>:<br>
        <?php echo $recipientObj->email ?>
    <?php elseif(isset($recipientsEmailsLinks)) : ?>    
        <br />
        <?php echo Yii::t('contact', 'To'); ?>:<br>
        <?php echo $recipientsEmailsLinks ?>
    <?php else: ?>
        .<br />
    <?php endif; ?>
</p>
<hr />
<p>
    <?php if ($email->recipientItemId) : ?>
        <?php if($email->getScenario() == 'send_to_many') : ?>
                <b><?php echo Yii::t('common', 'To'); ?>:</b><br />
        <?php else:?>
            <b><?php echo Yii::t('contact', 'Concerns'); ?>:</b><br />
        <?php endif; ?>
        <?php echo $email->recipientItemName; ?><br />
    <?php endif; ?>
    <b><?php echo Yii::t('contact', 'Message'); ?>:</b><br />    
    <b style="font-style:italic;"><?php echo $email->subject; ?></b><br />
    <?php echo nl2br($email->message); ?><br />
</p>