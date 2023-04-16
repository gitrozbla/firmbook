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
<p>    
    <?php echo Yii::t('site', 'FIRMBOOK SYSTEM POLAND informs', [], null, $user->language); ?><br />
</p>
<hr />
<?php 
    $url = $this->createAbsoluteUrl(
        'user/profile', array(
        'username' => $user->username)
    );
    $userLink = Html::link($user->username, $url, array('target'=>'_blank'));
?>
<?php if (!empty($user)) {
    echo '<p><b>' . Yii::t('user', 'Welcome', [], null, $user->language) . ' ' . $userLink . '</b></p>';
} ?>
<p>
    <?php 
        /*echo Yii::t('packages', 
            $emailMsg, ['{package}'=>$user->packageName()], null, $user->language);*/
        echo $emailMsg;
    ?>
    <br />
</p>
<p>
    <?php 
        $link = $this->createAbsoluteUrl('packages/history');
        echo Html::link($link, $link); 
    ?><br />
</p>
<?php /*
<p>
	<?php echo Yii::t('packages', 'Use the tools, capabilities and resources for transfer your business to the internet, to promote it, positioning etc. Take advantage of all the platform\'s functionalities. The best results are achieved after all functions are used.
To get a discount coupon for firmbook\'s e-services (packages, banners) send an SMS tekst PROMOTION to the number <a href="tel:500251861">500 251 861</a> or just a call.', [], null, $user->language);
    ?>	
</p>?>
<?php /*
<p>
    <?php echo Yii::t('register', 
            'If you haven\'t been registering on {name} ignore this message. <br />'
            . 'If you recieve from us unwanted messages please contact us.', 
            array('{name}' => '<a href="' . Yii::app()->request->hostInfo . '">'
                . substr(Yii::app()->request->hostInfo, 8) .'</a>'), null, $user->language); ?>
</p> */?>
<?php   
    $brand2 = Yii::app()->params['branding'];
    $colors2 = $brand2['colors'];
?>
<div style="
        padding: 15px 25px;
        color: <?php echo $colors2['logo']; ?>;
        background-color: <?php echo $colors2['bottom']; ?>;
        border: solid 1px rgba(0, 0, 0, 0.15);
        border-left: none;
        border-right: none;
    ">
   <p>
       <?php 
           $link = $this->createAbsoluteUrl('/packages/comparison');
           $link3 = $this->createAbsoluteUrl('/packages/comparison');
           echo Yii::t('site', 
               'Use the firmbook\'s tools, possibilities and solutions for transfer business to the internet and promote it, advertise, boost positioning in web searches and finding new business contacts... Use all the functionalities of the firmbook system. You will achieve the best results after applying all the functions. Use all the  system {link1}. The best results are achieved after all functions are used.<br>To get a discount coupon for e-services ({link2}) send an SMS text: PROMOTION to the number +48 500 251 861 or just call', 
               array(
                   '{link1}' => Html::link(Yii::t('site', 'functionalities', [], null, $user->language), $link),
                   '{link2}' => Html::link(Yii::t('site', 'packages', [], null, $user->language), $link),
                   '{link3}' => Html::link(Yii::t('site', 'banners', [], null, $user->language), $link3)
                   ), null, $user->language); ?>
   </p>
</div>