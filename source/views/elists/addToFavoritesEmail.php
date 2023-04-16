<?php 
/**
 * Wiadomość email o dodaniu firmy do ulubionych.
 * @category views
 * @package main
 * @author 
 * @copyright 
 */ 
?>
<?php
    if($type==Elist::ITEM_TYPE_ITEM)
        $link = '<b>'.Html::link($item->name, $item->url(true), array('target'=>'_blank')).'</b>'; 
    $url = $this->createAbsoluteUrl(
                'user/profile', array(
                    'username' => Yii::app()->user->name)
        );
    $userLink = '<b>'.Html::link(Yii::app()->user->name, $url, array('target'=>'_blank')).'</b>';
    if($type==Elist::ITEM_TYPE_USER)
        $message = Yii::t('elists', 'You have been added to favorites of user {user_name}.', array('{user_name}' => $userLink), null, $type==Elist::ITEM_TYPE_USER ? $item->language : $item->user->language);
    else
        $message = Yii::t('elists', 'Your '.$item->typeName().' {item_name} has been added to favorites of user {user_name}.', array('{item_name}' => $link, '{user_name}' => $userLink), null, $type==Elist::ITEM_TYPE_USER ? $item->language : $item->user->language);
?>
<p>
    <?php // echo Yii::t('elists', 'Firmbook System Poland informs', [], null, $type==Elist::ITEM_TYPE_USER ? $item->language : $item->user->language); ?> 
    <?php echo Yii::t('site', 'FIRMBOOK SYSTEM POLAND informs', [], null, $item->user->language); ?>
</p>
<hr />
<p>
    <?php 
        echo $message;
    ?>
</p>