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
    <?php echo Yii::t('packages', 
            'Package will expire on').' '.$package_expire;
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
    <?php echo Yii::t('register', 
            'If you haven\'t been registering on {name} ignore this message. <br />'
            . 'If you recieve from us unwanted messages please contact us.', 
            array('{name}' => '<a href="' . Yii::app()->request->hostInfo . '">'
                . substr(Yii::app()->request->hostInfo, 7) .'</a>')); ?>
</p>