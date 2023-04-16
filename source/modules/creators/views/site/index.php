<div class="home-big-picture">
    <div class="container">
        <div class="row">
            <div class="span6">
                <div class="home-title">
                    <h1>
                        <img src="images/branding-creators/firmbook-logo.png" alt="Firmbook" /><br />
                        <?php echo Yii::app()->params['branding']['title']; ?>
                    </h1>
                    <p class="text-right">
                        <?php echo Yii::t('CreatorsModule.site', 'Let\'s build a website!'); ?></p>
                    <p>
                        <?php $this->widget(
                            'bootstrap.widgets.TbButton',
                            array(
                                'id' => 'home-read-more',
                                'label' => Yii::t('CreatorsModule.site', 'Learn more'),
                                'type' => 'primary',
                                'size' => 'large',
                                'url' => '#'
                            )
                        ); ?>
                    </p>
                    <div class="well home-register-box">
                        <h2>
                            <?php echo Html::link(
                                    Yii::t('CreatorsModule.site', 'Join us'),
                                    $this->createFirmbookUrl('account/register'),
                                    array('target'=>'_blank')
                                    ); ?>
                        </h2>
                        <p class="quiet">
                            <?php echo Yii::t('CreatorsModule.site', 'To use Creators services you will need Firmbook account.'); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="well home-login-box">
                    <h2>
                        <?php echo Yii::t('CreatorsModule.site', 'Login using your Firmbook account'); ?>
                    </h2>
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
                            &nbsp;&nbsp;&nbsp;
                            <?php echo Html::link(Yii::t('account', 'Forgot your password?'),
                            		$this->createUrl('account/access_recovery'),
                                    //$this->createFirmbookUrl('account/access_recovery'), 
                                    array(
                                        'target' => '_blank',
                                        'class' => 'indent',
                                    )); ?>
                        </p>
                    <?php $this->endWidget(); ?>
                        
                    <hr />
                    
                    <h2>
                        <?php echo Yii::t('CreatorsModule.site', '... or connect with'); ?>
                    </h2>
                    <?php $this->widget('ext.eauth.EAuthWidget', array('action' => 'site/remote_login')); ?>
                    <?php /*<fb:login-button scope="public_profile,email" onlogin="checkLoginState();" data-size="large"></fb:login-button>	
                    <?php $this->widget('bootstrap.widgets.TbButton', array(
                        'label' => '<i class="fa fa-facebook-square"></i>&nbsp;&nbsp;&nbsp;Log in', 
                        'encodeLabel' => false, 
                        'type' => 'info')); ?> */?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="slide-down-icon slide-down-icon-hidden">
        <a href="#">
            <i class="fa fa-angle-double-down"></i>
        </a>
    </div>
</div>

<div class="container home-description">
    <div class="row">
        <div class="span6">
            <div class="home-description-left lead">
                <p class="prepare-slide hidden-slide-from-left">
                    <?php if ($articleDescription) {
                        echo Yii::t('article.content', '{'.$articleDescription->alias.'}', 
                                array('{'.$articleDescription->alias.'}'=>$articleDescription->content), 
                                'dbMessages');
                    } ?>
                    <?php /*Firmbook Creators provides you a simple way to create 
                    full featured website which is ready to host.
                    All you need is to login to your Firmbook account and 
                    generate website files. No coding at all!*/ ?>
                </p>
            </div>
        </div>
        <div class="span6">
            <div class="home-description-right lead">
                <?php echo Html::link(
                        Html::image('images/branding-creators/home-graph-icons.png', 'how Creators works'),
                        $this->createUrl('/pages/show', array(
                            'name'=>Yii::t('CreatorsModule.article', 'help'))
                        ),
                        array('target'=>'_blank')
                ); ?>
                <ul>
                    <li><?php echo Yii::t('CreatorsModule.site', 'Use creator'); ?></li>
                    <li><?php echo Yii::t('CreatorsModule.site', 'Download files'); ?></li>
                    <li><?php echo Yii::t('CreatorsModule.site', 'Host website'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="home-firmbook-description">
    <div class="container">
        <div class="row">
            <div class="span6 home-firmbook-logo">
                <a href="<?php echo Yii::app()->params['firmbookUrl']; ?>" target="_blank">
                    <img src="images/branding-creators/firmbook_description_logo.jpg" />
                </a>
            </div>
            <div class="span6">
                <p class="lead text-center prepare-slide hidden-slide-from-right">
                    <?php echo Yii::t('CreatorsModule.site', 'Not using Firmbook yet?'); ?>
                    <a href="<?php echo Yii::app()->params['firmbookUrl']; ?>" target="_blank">
                        <?php echo Yii::t('CreatorsModule.site', 'Check benefits'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>