<script type="text/javascript" src="<?php echo $this->mapFile('js/creators/jquery-1.11.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo $this->mapFile('js/creators/bootstrap.min.js'); ?>"></script>
<?php /* bootstrap-lightbox */ ?>
<script type="text/javascript" src="<?php echo $this->mapFile('/js/creators/lightbox/js/lightbox.min.js', 'lightbox/js'); ?>"></script>
<?php  /* style w head.php */ ?>

<script>
    jQuery(function($){
        $('.popover-trigger').popover({
            trigger: 'hover',
            html: true
        });
    });
</script>

<?php if ($this->previewMode) : ?>
    <script>
        if (window==window.top) {   // should be iframe
            window.top.location.href = 
            '<?php echo $this->createUrl('generator/editor', array(
                    'id' => $website->company_id
                )); ?>';
        }
        
        if (typeof window.top.jQuery != 'undefined' 
                && typeof window.top.previewUpdate != 'undefined') {
            window.top.jQuery(function($){
                window.top.previewUpdate();
            });
        }
    </script>
<?php else : ?>
    <script type="text/javascript" src="<?php echo $this->mapFile('js/cookies-alert.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo $this->mapFile('js/old-browser-alert.js'); ?>"></script>
    <script type="text/javascript">
        var cookiesAlert = '<?php echo Yii::t('site', '{cookiesAlert}', 
                array('{cookiesAlert}' => '')); ?>';
        var oldBrowserAlert = '<?php echo Yii::t('site', '{oldBrowserAlert}', 
                array('{oldBrowserAlert}' => '')); ?>';
    </script>
<?php endif; ?>