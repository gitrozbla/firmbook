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
    <?php echo Yii::t('contact', 'This message was sent using contact form on website', [], null, $recipient->language); ?> 
    <?php 
        /*$url = $this->createAbsoluteUrl(
                Yii::app()->controller->id.'/'.Yii::app()->controller->action->id
        );*/
        $url = Yii::app()->request->urlReferrer;
        echo Html::link($url, $url, array('target'=>'_blank')); 
    ?>.<br />
</p>
<hr />
<p>
    <b><?php echo Yii::t('user', 'Contact information', [], null, $recipient->language); ?>:</b><br />
    <?php echo $user->forename; ?> <?php echo $user->surname; ?><br />
    <?php if (!empty($user->phone)) : ?>
        <?php echo Html::link(
                $user->phone, 
                'tel:'.str_replace(
                    array(' ', '-', '+', '(', ')'), 
                    '', 
                    $user->phone)
        ); ?><br />
    <?php endif; ?> 
    <?php if (!empty($user->email)) : ?>
        <?php echo Html::link(
                $user->email, 
                'mailto:'.$user->email
        ); ?><br />
    <?php endif; ?>
</p>
<hr />
<p>    
    <b><?php echo Yii::t('contact', 'Message', [], null, $recipient->language); ?>:</b><br />    
    <b style="font-style:italic;"><?php echo $email->subject; ?></b><br />
    <?php echo nl2br($email->message); ?><br />
</p>