<?php 
/**
 * Wiadomość email potwierdzająca rejestrację.
 *
 * @category views
 * @package account
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<p>
    <?php echo Yii::t('register', 
            'Welcome to {name} service! <br />'
            . 'To confirm your registration, click link below.', 
            array('{name}' => Yii::app()->name)); ?>
    <br />
</p>

<p>
    <?php 
        $link = $this->createAbsoluteUrl('account/register_confirm', array(
            'username' => $user->username,
            'verification_code' => $user->verification_code,
        ));
        echo Html::link($link, $link); 
    ?><br />
</p>

<p>
    <?php echo Yii::t('register', 
            'If you haven\'t been registering on {name} ignore this message. <br />'
            . 'If you recieve from us unwanted messages please contact us.', 
            array('{name}' => '<a href="' . Yii::app()->request->hostInfo . '">'
                . substr(Yii::app()->request->hostInfo, 7) .'</a>')); ?>
</p>