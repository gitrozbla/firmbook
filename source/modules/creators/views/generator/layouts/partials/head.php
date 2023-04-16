<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width" />

<?php
    $website = $this->website;
    
    if ($this->id != 'generator') {
        throw new CException(500, 'Must be run from generator controller!');
    }
    
    // ścieżka dla plików statycznych
    if ($this->previewMode) {
        // preview
        $absoluteBaseUrl = (Yii::app()->request->getHostInfo()).(!empty(Yii::app()->baseUrl) ? (Yii::app()->baseUrl) : '');
        /*$absoluteBaseUrl .= $this->createUrl('generator/preview', array(
            'website' => $website->company_id,
            'url' => ''
        )).'/';*/

        echo '<base href="'.$absoluteBaseUrl.'" />';
    }
    // ścieżka dla podstron
    $absoluteHomeUrl = $this->createUrl('/').'/';

    $cs = Yii::app()->getClientScript();
    
    $cs->reset();
?>


<?php if($website->favicon) {
    echo '<link rel="icon" href="'.$this->mapFile('files/CreatorsWebsite/'.$website->company_id.'/'.$website->favicon).'">';
} ?>

<?php 
    if ($website->meta_title) {
        echo '<title>'.Html::encode($website->meta_title).'</title>'."\n";
    }
    if ($website->meta_description) {
        echo '<meta name="description" content="'.Html::encode($website->meta_description).'" />'."\n";
    }
    if ($website->meta_keywords) {
        echo '<meta name="keywords" content="'.Html::encode($website->meta_keywords).'" />'."\n";
    } 
?>

<?php 
    // css
    
    // theme
    //$cs->registerCssFile('/css/creators_themes/'.$website->theme.'.min.css', '', true);
    $cs->registerLinkTag('stylesheet', 'text/css', $this->mapFile('/css/creators_themes/'.$website->theme.'.min.css'), null, array(
        'class' => 'theme',
        'data-path' => $this->previewMode ? '/css/creators_themes/' : null
    ));
    if (true) { // package check (also in <html>)
        $cs->registerCssFile($this->mapFile('/css/creators_themes/bootstrap-responsive.min.css'), '', true);
    }
    
    // font
    $cs->registerCssFile($this->mapFile('/css/font-awesome-4.3.0/css/font-awesome.min.css', 'font-awesome/css'), '', true);
    // assets
    $this->mapFile('/css/font-awesome-4.3.0/fonts/FontAwesome.otf', 'font-awesome/fonts');
    $this->mapFile('/css/font-awesome-4.3.0/fonts/fontawesome-webfont.eot', 'font-awesome/fonts');
    $this->mapFile('/css/font-awesome-4.3.0/fonts/fontawesome-webfont.svg', 'font-awesome/fonts');
    $this->mapFile('/css/font-awesome-4.3.0/fonts/fontawesome-webfont.ttf', 'font-awesome/fonts');
    $this->mapFile('/css/font-awesome-4.3.0/fonts/fontawesome-webfont.woff', 'font-awesome/fonts');
    $this->mapFile('/css/font-awesome-4.3.0/fonts/fontawesome-webfont.woff2', 'font-awesome/fonts');
    
    // bootstrap-lightbox
    $cs->registerCssFile($this->mapFile('/js/creators/lightbox/css/lightbox.css', 'lightbox/css'), '', true);
    // skrypt w bootom.php
    // assets
    $this->mapFile('/js/creators/lightbox/img/close.png', 'lightbox/img');
    $this->mapFile('/js/creators/lightbox/img/loading.gif', 'lightbox/img');
    $this->mapFile('/js/creators/lightbox/img/next.png', 'lightbox/img');
    $this->mapFile('/js/creators/lightbox/img/prev.png', 'lightbox/img');
    
    // cookies-alert
    if ($this->previewMode == false) {
        $cs->registerLinkTag('stylesheet', 'text/css', $this->mapFile('/css/cookies-alert.css'), null, array(
            'class' => 'cookies-alert-css'
        ));
    }
    
    // overrides
    $cs->registerCssFile($this->mapFile('/css/creators_themes/theme-overrides.css'), '', true);    