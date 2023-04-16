<?php 
/**
 * Wiadomość email o zblizajacym sie wygasnieciu pakietu
 *
 * @category views
 * @package account
 * @author
 * @copyright (C) 2015
 */ 
?>
<?php if (!empty($user)) {
    echo '<p><b>' . Yii::t('user', 'Welcome', [], null, $user->language) . ' ' . $user->username . '</b></p>';
} ?>
<p>
    <?php echo Yii::t('packages', 
            'Package will expire on', [], null, $user->language).' '.$package_expire;
    ?>
    <br />
</p>
<p>
    <?php 
        $link = $this->createAbsoluteUrl('packages/history');
        echo Html::link($link, $link); 
    ?><br />
</p>
<p>
    <?php echo Yii::t('packages', 'Use the tools, capabilities and resources for transfer your business to the internet, to promote it, positioning etc. Take advantage of all the platform\'s functionalities. The best results are achieved after all functions are used.
To get a discount coupon for firmbook\'s e-services (packages, banners) send an SMS tekst PROMOTION to the number <a href="tel:500251861">500 251 861</a> or just a call.', [], null, $user->language);
    ?>	
</p>
<?php /*
<p>
    <?php echo Yii::t('register', 
            'If you haven\'t been registering on {name} ignore this message. <br />'
            . 'If you recieve from us unwanted messages please contact us.', 
            array('{name}' => '<a href="' . Yii::app()->request->hostInfo . '">'
                . substr(Yii::app()->request->hostInfo, 8) .'</a>'), null, $user->language); ?>
</p>*/ ?>