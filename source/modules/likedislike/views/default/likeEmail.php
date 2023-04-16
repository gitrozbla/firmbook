<?php 
/**
 * Wiadomość email o polubieniu.
 * @category views
 * @package main
 * @author
 * @copyright
 */ 
?>
<?php
    $link = '<b>'.Html::link($item->name, $item->url(true), array('target'=>'_blank')).'</b>'; 
    $url = $this->createAbsoluteUrl(
        '/user/profile', array(
            'username' => Yii::app()->user->name)
    );
    $userLink = '<b>'.Html::link(Yii::app()->user->name, $url, array('target'=>'_blank')).'</b>';
    $message = Yii::t('likedislikeModule.main', '{user_name} liked your '.$item->typeName().' {item_name}.', array('{item_name}' => $link, '{user_name}' => $userLink), null, $item->user->language);    

?>
<p>
    <?php echo Yii::t('elists', 'Firmbook System Poland informs', [], null, $item->user->language); ?>     
</p>
<hr />
<p>
    <?php 
        echo $message;
    ?>
</p>