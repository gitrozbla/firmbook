<?php 
/**
 * 
 * @category views
 * @package main
 * @author 
 * @copyright 
 */ 
?>
<?php
    $itemLink = '<b>'.Html::link($item->name, $item->url(true), array('target'=>'_blank')).'</b>';
    if($company)
    {    
        $companyLink = '<b>'.Html::link($company->item->name, $company->item->url(true), array('target'=>'_blank')).'</b>';
        $message = Yii::t('services', '{company_name} has added a new service {item_name}.', array('{item_name}' => $itemLink, '{company_name}' => $companyLink), null, $recipient['language']);
    } else
    {
        $url = $this->createAbsoluteUrl(
        'user/profile', array(
        'username' => $item->user->username)
        );
        $userLink = '<b>'.Html::link($item->user->username, $url, array('target'=>'_blank')).'</b>';
        $message = Yii::t('services', '{user_name} has added a new service {item_name}.', array('{item_name}' => $itemLink, '{user_name}' => $userLink), null, $recipient['language']);
    }    
?>
<p>
    <?php // echo Yii::t('elists', 'Firmbook System Poland informs', [], null, $recipient['language']); ?> 
    <?php echo Yii::t('site', 'FIRMBOOK SYSTEM POLAND informs', [], null, $recipient['language']); ?>
</p>
<hr />
<p>
    <?php 
        echo $message;
    ?>
</p>