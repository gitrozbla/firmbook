<?php 
/**
 * Wiadomość email pozwalająca przywrócić dostęp.
 *
 * @category views
 * @package account
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<p>
    <?php echo Yii::t('accessRecovery', 
            'This message is a part of verification process of user.<br />'
            . 'For recover access to {name} proceed with link below:<br />'
            . 'Remember to not to share your access data to anyone.',
            array('{name}'=>Yii::app()->name)); ?>
</p>

<p>
    <?php 
        $link = $this->createAbsoluteUrl('account/type_password', array(
            'username' => $model->username,
            'recovery_code' => $recoveryCode));
        echo Html::link($link, $link, array('target'=>'_blank')); 
    ?><br />
</p>