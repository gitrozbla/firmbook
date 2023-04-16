<?php 
/**
 * Wiadomość email z postem.
 *
 * @category views
 * @package newsletter
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<h2><?php echo $post->subject; ?></h2>
<?php echo $post->content; ?>
<p style="font-size: 0.9em; color: gray;">
    <?php echo Yii::t('newsletter', 'You are subscribed to newsletter on {domain}. '
            . 'If you don\'t want to receive messages, {link}.', array(
                '{domain}'=>Yii::app()->name,
                '{link}'=>Html::link(
                        Yii::t('newsletter', 'contact us'), 
                        'mailto:'.Yii::app()->params['branding']['email']
                        )
                )
            ); ?>
</p>