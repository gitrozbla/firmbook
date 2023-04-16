<?php 
/**
 * Layout główny strony.
 *
 * @category views
 * @package main
 * @author BAI
 * @copyright (C) 2014 BAI
 */ 
?>
<?php $articleLandingPage = Article::model()->find(
    		'alias="package-to-low"'
    ); ?>    
<!DOCTYPE html>
<html class="no-js" lang="<?php echo Yii::app()->language; ?>">
    <head>
        <?php
            // ścieżka dla plików statycznych
            $absoluteBaseUrl = (Yii::app()->request->getHostInfo()).(!empty(Yii::app()->baseUrl) ? (Yii::app()->baseUrl.'/') : '');
            // ścieżka dla podstron
            $absoluteHomeUrl = $this->createUrl('/').'/';
            
            $cs = Yii::app()->getClientScript();
            $editor = Yii::app()->session['editor'];
            $search = Search::model()->getFromSession();
	?>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="pl" />
        <base href="<?php echo $absoluteBaseUrl; ?>" />
        <meta name="viewport" content="width=device-width">
        <?php //if (!empty($this->getCanonicalUrl())) : ?>
        <?php if (method_exists($this, 'getCanonicalUrl') && $this->getCanonicalUrl()) : ?>
        	<link rel="canonical" href="<?php echo CHtml::encode($this->getCanonicalUrl()); ?>">
        <?php endif; ?>	
        <?php if (method_exists($this, 'getRobotsIndex')) : ?>        
            <meta name="robots" content="<?php echo $this->getRobotsIndex() ? 'index' : 'noindex'; ?>, <?php echo $this->getRobotsFollow() ? 'follow' : 'nofollow'; ?>">
        <?php endif; ?>
        <link rel="shortcut icon" href="<?php echo $absoluteBaseUrl; ?>/images/branding/favicon.ico" />
		
        <meta property="og:title" content="<?php echo CHtml::encode($this->pageTitle); ?>" />
        <?php if (!empty($this->pageDescription)) : ?>
        <meta property="og:description" content="<?php echo CHtml::encode($this->pageDescription); ?>" />
        <?php endif; ?>
        <meta property="og:image" content="<?php echo $absoluteBaseUrl; ?>/images/branding/fb-logo.png" />
			       
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
	
        <?php /* css główny */ ?>
	<?php $cs->registerCssCrushFile(Yii::app()->basePath.'/../css/main.css', '', true); ?>
        <?php $cs->registerCssFile($this->createUrl('/site/css')); ?>
        <meta name="google-signin-client_id" content="620805939068-8lasopi0k0kllod9kinkadh444hmcmv3.apps.googleusercontent.com">
		        
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-68376173-1', 'auto');
          ga('send', 'pageview');

        </script>    
        
        <!-- Meta Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '904119896950397');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=904119896950397&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Meta Pixel Code -->

        <meta name="facebook-domain-verification" content="8xe519qpkjm7u9rbdrq8amfahffikr" />

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-58X33XJ');</script>
        <!-- End Google Tag Manager -->
        
        <?php if(method_exists($this, 'getLoadRecaptchaAPI') && $this->getLoadRecaptchaAPI()) :?>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>	        
        <?php endif; ?>
    </head>

    <body id="page-<?php echo Yii::app()->controller->id; ?>-<?php echo Yii::app()->controller->action->id; ?>">
            
        <div class="navbar-wrap">
            <?php require 'mainParts/navbar.php'; ?>
        </div>
        
        <div class="container">
            
            <?php if (Yii::app()->user->checkAccess('admin_panel')) {
            	//if (Yii::app()->user->isAdmin || Yii::app()->user->checkAccess('admin_panel')) {
                require 'mainParts/adminPanel.php';
            } ?>
            
            <?php if (Yii::app()->user->isGuest == false) {
                require 'mainParts/userPanel.php';
            } ?>
            
            <?php if ($this->id != 'account') : ?>
                <?php require 'mainParts/searchPanel.php'; ?>

                <?php $this->widget(
                    'bootstrap.widgets.TbBreadcrumbs',
                    array(
//                         'homeLink' => Html::link(Yii::t('site', 'Home'), $absoluteHomeUrl),
                    	'homeLink' =>  Html::link(Yii::t('search', Search::getContextLongLabel($search->action,$search->type)), $search->createUrl(null, array(Search::getContextUrlType($search->type).'_context'=>Search::getContextUrlAction($search->action,$search->type)), true)),
                        'links' => $this->breadcrumbs,
                    )
                ); ?>
            
            <?php endif; ?>
                        
            <div class="alerts">
                <noscript>
                    <div class="alert alert-error">
                        <strong><?php echo Yii::t('site', 'This website requires Javascript to be enabled!'); ?></strong>
                    </div>
                </noscript>
                
                <?php $this->widget('bootstrap.widgets.TbAlert'); ?>
            </div>
            <?php if(isset($this->tooLowPackage) && $this->tooLowPackage) : ?>
	            <?php $this->beginWidget(
	    		'bootstrap.widgets.TbHeroUnit',
	    		array(
	    			//'heading' => Yii::t('article.title', $articleLandingPage->title, array(), 'dbMessages').
	    			//'heading' => '<img src="images/branding/email-logo.png" alt="'.Yii::app()->params['branding']['title'].'"/>',
	    			'encodeHeading' => false,
	    			//'heading' => 'Witaj na Firmbook-u!',
	    			'htmlOptions' => array(
	    				'class' => 'text-center',	
	    				'style' => 'background: #efefef url(images/branding-creators/firmbook_description_logo.jpg); 
	    					padding-top:100px; 
	    					background-position: 50% -100px;
	    					background-repeat: no-repeat;',
	    				//background-repeat: no-repeat;	
	    				//'style' => 'background-color: rgba(0,165,0,0.1); cursor: pointer',
	    				//'style' => 'background-color: rgba(92,170,229,0.1); cursor: pointer',
	    				//'onclick' => 'document.location.href="http://onet.pl"'
	    				'onclick' => 'document.location.href="'.$this->createAbsoluteUrl('account/register').'"'	
	    							
	    			)			
	    		)); ?>    	
	    		<?php echo Yii::t('article.content', '{'.$articleLandingPage->alias.'}', array('{'.$articleLandingPage->alias.'}'=>$articleLandingPage->content), 'dbMessages'); ?>
	    		<?php echo $this->tooLowPackageMessage ?>
	    		
	    		<?php //<p class="lead primary">Firmbook - pierwsza platforma business community. Nie zwlekaj - utwórz darmowe konto i zacznij korzystać z narzędzi, możliwości i środków udostępnianych na firmbooku.</p>?>
	    		<?php $this->widget(
	                    'bootstrap.widgets.TbButton',
	                    array(
	                        'label' => Yii::t('site', 'Increase Package'),
	                        'url' => array('account/register'),
	                        'type' => 'primary',
	                    	'size' => 'large',	
	                        'htmlOptions' => array(
	                            'class' => 'top-10',
	                        ),
	                    )
	                ); ?>
	                
	    		<?php $this->endWidget(); ?>
	    		
            <?php else: ?>
            <div class="content">
                <?php echo $content; ?>            
            </div>
			<?php endif; ?>
        </div>
        
        <hr />
        
        <div class="container footer text-center">
            <div class="row">
                <div class="span12">
                    <div class="menu-bottom">
                        <?php $this->widget('bootstrap.widgets.TbMenu', array(
                            'type' => 'pills',
                            'items' => array(
                                array(
                                    'label' => Yii::t('site', 'Home'),
                                    'url' => $absoluteHomeUrl,
                                ),
                                array(
                                    'label' => Yii::t('article', 'Privacy Agreement'),
                                    'url' => $this->createUrl('/pages/show', array('name'=>Yii::t('article', 'privacy-agreement'))),
                                ),
                                array(
                                    'label' => Yii::t('article', 'Terms of Use'),
                                    'url' => $this->createUrl('/pages/show', array('name'=>Yii::t('article', 'terms-of-use'))),
                                ),
                                /*array(
                                    'label' => Yii::t('article', 'About us'),
                                    'url' => $this->createUrl('/pages/show', array('name'=>Yii::t('article', 'about-us'))),
                                ),*/
                                /*array(
                                    'label' => Yii::t('article', 'Faq'),
                                    'url' => $this->createUrl('/pages/show', array('name'=>Yii::t('article', 'faq'))),
                                ),*/
                                array(
                                    'label' => Yii::t('contact', 'Contact'),
                                    'url' => $this->createUrl('/site/contact'),
                                ),
                                array(
                                    'label' => Yii::t('account', 'Account'),
                                    'url' => $this->createUrl('/user/profile'),
                                	'linkOptions' => array(
                                		'rel' => 'nofollow'
                                	)
                                ),
                                array(
                                    'label' => Yii::t('article', 'Packages'),
                                	'url' => $this->createUrl('/packages/comparison'),
                                    //'url' => $this->createUrl('/pages/show', array('name'=>Yii::t('article', 'packages'))),
                                ),
                            	array(
                            		'label' => 'Creators',
                            		'url' => Yii::app()->params['creatorsUrl'],
                            		//'url' => $this->createCreatorsUrl('/'),
                            	),
                                /*array(
                                    'label' => Yii::t('pages', 'Articles'),
                                    'url' => $this->createUrl('/articles/index'),
                                ),
                                array(
                                    'label' => Yii::t('pages', 'Advertisement'),
                                    'url' => $this->createUrl('/ad/index'),
                                ),*/
                                array(
//                            		'icon' => '<i class="fa fa-facebook" aria-hidden="true"></i>',
                                    'icon' => 'fa fa-facebook',
                            		'url' => "https://www.facebook.com/sharer.php?u=" . $absoluteBaseUrl,
                            		//'url' => $this->createCreatorsUrl('/'),
                            	),
                                array(
//                            		'icon' => '<i class="fa fa-facebook" aria-hidden="true"></i>',
                                    'icon' => 'fa fa-twitter',
                            		'url' => "https://twitter.com/share?url=" . $absoluteBaseUrl,
                            		//'url' => $this->createCreatorsUrl('/'),
                            	),
                            ),
                            'htmlOptions' => array(
                                'class' => 'nav-pills-center'
                            ),
                        )); ?>
                    <?php if (Yii::app()->params['branding']['bottomImage']) : ?>
                    	<?php if(Yii::app()->language == 'pl'): ?>
                    		<img src="images/branding/RPO_WL_kolorowe_wyszycie_800x143_72dpi.png" alt="<?php echo Yii::app()->params['branding']['bottomImage']; ?>" />
                    	<?php else: ?>
                    		<img src="images/branding/RPO_WL_kolorowe_wyszycie_eng_800x173_72dpi.png" alt="<?php echo Yii::app()->params['branding']['bottomImage']; ?>" />
                    	<?php endif; ?>
                        <?php /*<img src="images/branding/bottom-image2.jpg" alt="<?php echo Yii::app()->params['branding']['bottomImage']; ?>" />*/?>
                    <?php endif; ?>
                        <div class="muted"><small><?php echo Yii::app()->params['branding']['copyrightInfo']; ?></small></div>
                </div>
            </div>
        </div>
        
        <?php $cs->registerScriptFile(Yii::app()->homeUrl.'js/main.js', CClientScript::POS_END); ?>
        <?php if (empty($_COOKIE['cookiesAccepted'])) : 
                // because CCookieCollection stores only validated cookies ?>
            <script type="text/javascript" src="js/cookies-alert.js"></script>
            <script type="text/javascript" src="js/old-browser-alert.js"></script>
            <script type="text/javascript">
                var cookiesAlert = '<?php echo Yii::t('site', '{cookiesAlert}', 
                        array('{cookiesAlert}' => '')); ?>';
                var oldBrowserAlert = '<?php echo Yii::t('site', '{oldBrowserAlert}', 
                        array('{oldBrowserAlert}' => '')); ?>';
            </script>
        <?php endif; ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-58X33XJ"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->    
    </body>
</html>
