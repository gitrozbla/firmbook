<?php 
/**
 * Formularz rejestracyjny.
 *
 * @category views
 * @package account
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<div class="row">
    <div class="span6">
        
        <?php $form = $this->beginWidget('ActiveForm', array(
            'type' => 'horizontal',
            'htmlOptions' => array('class' => 'well center', 'id' => 'fromWithRecaptcha'),
        )); ?>
        
            <h1><?php echo Yii::t('register', 'Start your business with'); ?> <?php echo Yii::app()->name; ?></h1>
            
            <hr />
            
            <?php echo Yii::t('register', 'Please fill this simple form, it takes less than minute.'); ?>
            
            <hr />
            
            <?php echo $form->textFieldRow($user, 'email'); ?>
            
            <?php echo $form->textFieldRow($user, 'username'); ?>

            <?php echo $form->passwordFieldRow($user, 'password'); ?>

            <?php echo $form->passwordFieldRow($user, 'passwordRepeat'); ?>
        
            <?php echo $form->checkboxRow($user, 'termsAccept', array(), array(
                'hint'=>Html::link('('.Yii::t('register', 'Show terms of use').')', 
                    $this->createUrl('pages/show', array('name'=>'terms-of-use')),
                    array('target'=>'_blank'))
                )); ?>
            
            <?php /*if (CCaptcha::checkRequirements()): ?>
                <div class="indent-60">
                    <?php $this->widget('CCaptcha'); ?>
                </div>
                <?php echo $form->textFieldRow($user, 'verifyCode', array(), array(
                    'hint' => Yii::t('register', 
                            'Please retype code from picture above.<br />'
                            . 'Letters are not case-sensitive.'
                            )
                )); ?>
                
            <?php endif;*/ ?>
            <?php $this->widget('application.components.widgets.ReCaptcha', array('url'=>$this->createUrl('site/recaptcha'))); ?>
            <hr />
            <?php $this->widget('bootstrap.widgets.TbButton', array(
//                'buttonType' => 'submit', 
                'label' => Yii::t('register', 'Register'), 
                'type' => 'primary',
                'htmlOptions' => array(
                    'class' => 'form-indent',
                    'id' => 'submitBtnWithRecaptcha'
                ))); ?>

        <?php $this->endWidget(); ?>
            
        <?php Yii::app()->clientScript->registerScript(
                'username-autofill',
                "   var emailPart, username;
                    function emailCrop(email) {
                        var atPos = email.indexOf('@');
                        if (atPos != -1) {
                            return email.substring(0, atPos);
                        } else {
                            return email;
                        }
                    }
                    $('#User_email, #User_username').on('focus', function(){
                        emailPart = emailCrop($('#User_email').val());
                        username = $('#User_username').val();
                    });
                    $('#User_email').on('keyup change', function(){
                        
                        if (username == '' || username == emailPart) {
                            $('#User_username').val(emailCrop($(this).val()));
                        }
                    });"
                ); ?>

    </div>
    
    <div class="span6">
        <h2><?php echo Yii::t('register', 'Already have an account?'); ?></h2>
        
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('account', 'Login'), 
            'type' => 'primary',
            'url' => $this->createUrl('login')
            )); ?>
        
        <br /><br /><br />    <?php /*connect with facebook*/ ?>
        <br /><br /><br />    <?php /*login with google*/ ?>
        <br />
        
        <h2><?php echo Yii::t('article.title', $articleRight1->title, array(), 'dbMessages'); ?></h2>

        <?php echo Yii::t('article.content', '{'.$articleRight1->alias.'}', array('{'.$articleRight1->alias.'}'=>$articleRight1->content), 'dbMessages'); ?>
        
        <h2><?php echo Yii::t('article.title', $articleRight2->title, array(), 'dbMessages'); ?></h2>

        <?php echo Yii::t('article.content', '{'.$articleRight2->alias.'}', array('{'.$articleRight2->alias.'}'=>$articleRight2->content), 'dbMessages'); ?>
        
    </div>
</div>
