<?php 
/**
 * Wiadomość email po dodaniu do obserwowanych.
 * @category views
 * @package main
 * @author 
 * @copyright 
 */ 
?>
<?php
//    $user = Yii::app()->user->getModel();
    if($type==Follow::ITEM_TYPE_COMPANY)
        $link = '<b>'.Html::link($item->name, $item->url(true), array('target'=>'_blank')).'</b>'; 
    $url = $this->createAbsoluteUrl(
        'user/profile', array(
        'username' => Yii::app()->user->name)
    );
    $userLink = '<b>'.Html::link(Yii::app()->user->name, $url, array('target'=>'_blank')).'</b>';
    if($type==Follow::ITEM_TYPE_USER)
        $message = Yii::t('follow', 'You have been added to the watched of user {user_name}.', array('{user_name}' => $userLink), null, $owner->language);
    else
        $message = Yii::t('follow', 'Your company {item_name} has been added to observed of user {user_name}.', array('{item_name}' => $link, '{user_name}' => $userLink), null, $owner->language);
?>
<p>
    <?php // echo Yii::t('elists', 'Firmbook System Poland informs', [], null, $owner->language); ?> 
    <?php echo Yii::t('site', 'FIRMBOOK SYSTEM POLAND informs', [], null, $owner->language); ?>
</p>
<hr />
<p>
    <?php 
        echo $message;
    ?>
</p>