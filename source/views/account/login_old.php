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

                    <?php echo $form->checkBoxRow($user, 'remember_me'); ?>

                    <?php $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType'=>'submit', 
                        'label'=>Yii::t('account', 'Login'), 
                        'type'=>'primary')); ?>
                    
                    <?php echo Html::link(Yii::t('account', 'Forgot your password?'), 
                            array('account/access_recovery'), 
                            array('class'=>'indent')); ?>
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
            <br /><br /><br />    <?php /*connect with facebook*/ ?>
            
            <!--
			  Below we include the Login Button social plugin. This button uses
			  the JavaScript SDK to present a graphical Login button that triggers
			  the FB.login() function when clicked.
			-->
			
			<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
			</fb:login-button>
			
			<div id="status">
			</div>
            
            <?php $this->widget(
                'bootstrap.widgets.TbButton',
                array(
                    'label' => Yii::t('account', 'Logout'),
                    'type' => 'primary',
                    //'url' => $this->createUrl('account/register'),
                    'htmlOptions' => array('onclick' => 'fblogout()'),
                ));
            ?>
            <?php /*<div
			  class="fb-like"
			  data-share="true"
			  data-width="450"
			  data-show-faces="true">
			</div>*/?>
            <?php /*<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
			</fb:login-button>
			<div id="status">
			</div> */?>
			
            <br /><br /><br />    <?php /*login with google*/ ?>
            <br />
        </p>
        
        <hr />
        
        <h2><?php echo Yii::t('article.title', $articleRight->title, array(), 'dbMessages'); ?></h2>

        <?php echo Yii::t('article.content', '{'.$articleRight->alias.'}', array('{'.$articleRight->alias.'}'=>$articleRight->content), 'dbMessages'); ?>
    </div>
</div>
<?php require '_remoteLogin.php'; ?>

