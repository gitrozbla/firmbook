<?php 
/**
 * FOrmularz logowania.
 *
 * @category views
 * @package account
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<div class="row">
    <div class="span6">
        <div class="well">
            <h1><?php echo Yii::t('account', 'Log in'); ?></h1>
            <hr />
            
            <?php $form = $this->beginWidget('ActiveForm', array(
                'id'=>'login-form',
            )); ?>
                <p class="center">
                    <?php echo $form->textFieldRow($user, 'emailOrUsername'); ?>
                    <?php echo $form->passwordFieldRow($user, 'password'); ?>
					<div class="clearfix"></div><br />
                    <?php //echo $form->checkBoxRow($user, 'remember_me'); ?>

                    <?php $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType'=>'submit', 
                        'label'=>Yii::t('account', 'Login'), 
                        'type'=>'primary')); ?>
                    
                    <?php echo Html::link(Yii::t('account', 'Forgot your password?'), 
                            array('account/access_recovery'), 
                            array('class'=>'indent')); ?>
                    <?php /*Yii::app()->clientScript->registerScript(
                'confirm-resend',
                "                     
                     $('#confirmResend').on('click', function(){
                        if($('#User_emailOrUsername').val().length) {
	                    	$(this).attr('href', $(this).attr('href')+'/'+$('#User_emailOrUsername').val())	                    	
                    	} else			
                    		return false;	
                    });		
                "
                ); ?>                        
                    <?php echo Html::link(Yii::t('account', 'Resend activation link'), 
                    		$this->createUrl('account/register_confirm_resend', array('username'=>'')),                    		 
                    		array('class'=>'indent', 'id'=>'confirmResend')); 
                   */ ?>        
                </p>
            <?php $this->endWidget(); ?>
        </div>
        
        <h2><?php echo Yii::t('article.title', $articleLeft->title, array(), 'dbMessages'); ?></h2>

        <?php echo Yii::t('article.content', '{'.$articleLeft->alias.'}', array('{'.$articleLeft->alias.'}'=>$articleLeft->content), 'dbMessages'); ?>
    </div>
    
    <div class="span6">
        <p class="lead"><?php echo Yii::t('account', 'Don\'t have an account?'); ?></p>
        <p>
            <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(
                    'label' => Yii::t('account', 'Register'),
                    'type' => 'primary',
                    'url' => $this->createUrl('account/register'),
                )
            ); ?>
            <br />    <?php /*connect with facebook*/ ?>
<p><?php echo Yii::t('common', 'or'); ?></p>
            <h2>Do you already have an account on one of these sites? Click the logo to log in with it here:</h2>            
<?php	

//$this->widget('ext.eauth.EAuthWidget');
	$this->widget('ext.eauth.EAuthWidget', array('action' => 'account/remote_login'));
    //$this->widget('ext.eauth.EAuthWidget', array('action' => 'site/login'));
?>
            <p><?php echo Yii::t('common', 'or'); ?></p>
            <!--
			  Below we include the Login Button social plugin. This button uses
			  the JavaScript SDK to present a graphical Login button that triggers
			  the FB.login() function when clicked.
			-->
			<fb:login-button scope="public_profile,email" onlogin="checkLoginState();" data-size="large">
			</fb:login-button>		
			
			
            <br /><br /><p><?php echo Yii::t('common', 'or'); ?></p>    <?php /*login with google*/ ?>
            
            <?php require '_remoteLoginGoogle4.php'; ?>
            
            
            <br />
        </p>
        
        <hr />
        
        <h2><?php echo Yii::t('article.title', $articleRight->title, array(), 'dbMessages'); ?></h2>

        <?php echo Yii::t('article.content', '{'.$articleRight->alias.'}', array('{'.$articleRight->alias.'}'=>$articleRight->content), 'dbMessages'); ?>
    </div>
</div>
<?php require '_remoteLogin.php'; ?>

