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
<?php if (!empty($user)) {
    echo '<p><b>' . Yii::t('user', 'Welcome') . ' ' . $user->username . '</b></p>';
} ?>
<p>
    <?php echo Yii::t('packages', 
            'Your changes have been introduced for the package.');
//,null, null, $purchaseUserData->language
    ?>
    <br />
</p>
<?php /*
<p>
    <?php echo Yii::t('register', 
            'If you haven\'t been registering on {name} ignore this message. <br />'
            . 'If you recieve from us unwanted messages please contact us.', 
            array('{name}' => '<a href="' . Yii::app()->request->hostInfo . '">'
                . substr(Yii::app()->request->hostInfo, 7) .'</a>')); ?>
</p>*/?>