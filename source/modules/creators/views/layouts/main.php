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
        <meta name="language" content="<?php echo Yii::app()->language; ?>" />
        <base href="<?php echo $absoluteBaseUrl; ?>" />
        <?php /*<meta name="viewport" content="width=device-width">*/ ?>
        
        <?php if ($this->customFavicon !== false) {
            if ($this->customFavicon) {
                echo '<link rel="icon" type="image/png" href="'.$this->customFavicon.'">';
            }
        } else {
            require 'partials/favicon.php';
        } ?>

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php /* css */ ?>
        <?php $cs->registerCssFile('/css/bootstrap-flatly.min.css', '', true); ?>
        <?php $cs->registerCssFile('/css/bootstrap-responsive.min.css', '', true); ?>
	<?php $cs->registerCssCrushFile(Yii::app()->basePath.'/../css/creators.css', '', true); ?>
	<?php $cs->registerCssFile($this->createUrl('/site/css')); ?>
	
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		  ga('create', 'UA-68376173-4', 'auto');
		  ga('send', 'pageview');
		
		</script>
        <?php if(method_exists($this, 'getLoadRecaptchaAPI') && $this->getLoadRecaptchaAPI()) :?>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>	        
        <?php endif; ?>
    </head>

    <body id="page-<?php echo Yii::app()->controller->id; ?>-<?php echo Yii::app()->controller->action->id; ?>">
        
        <?php if ($this->noPartials == false) : ?>
            <?php require 'partials/navbar.php'; ?>

            <div class="container">
                <?php if (Yii::app()->user->isAdmin) {
                    require 'partials/adminPanel.php';
                } ?>

                <?php if (Yii::app()->user->isGuest == false) {
                    require 'partials/userPanel.php';
                } ?>
            </div>

            <div class="container">
                <div class="alerts">
                    <noscript>
                        <div class="alert alert-error">
                            <strong><?php echo Yii::t('site', 'This website requires Javascript to be enabled!'); ?></strong>
                        </div>
                    </noscript>

                    <?php $this->widget('bootstrap.widgets.TbAlert'); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="<?php if ($this->noContainer == false) {echo 'container';} ?> content">
            <?php echo $content; ?>
        </div>
        
        <?php if ($this->noPartials == false) : ?>
            <?php require 'partials/footer.php'; ?>
        <?php endif; ?>
        
		<?php $cs->registerScriptFile(Yii::app()->homeUrl.'js/main.js', CClientScript::POS_END); ?>
        <?php $cs->registerScriptFile(Yii::app()->homeUrl.'js/creators.js', CClientScript::POS_END); ?>
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

    </body>
</html>
