<?php
$socialIconsLocation = 'footer';	// flag
require 'socialIcons.php';

$style = $website->footer_text ? '':'display:none;';
echo '<p class="footer-text" style="'.$style.'">'
        .$website->footer_text
    .'</p>';    // purified

if (true) {   // package check
  echo '<p>'
          .Yii::t('CreatorsModule.generator', 'This website was generated using')
          .' '
          .Html::link(
                  Yii::app()->params['branding']['title'],
                  Yii::app()->request->hostInfo,
                  array('target'=>'_blank'))
          .'.'
      .'</p>';
}
