<?php
/**
 * Widget reCaptcha
 * 
 * 
 * @category components
 * @package components\widgets
 * @author 
 * @copyright (C) 
 */
class ReCaptcha extends CWidget 
{
    /**
     * url do usugi reCaptcha Google
     * @var string
     */
    public $url = '';
    
    /**
     * Generuje reCaptcha i javascript do obsugi zdarz
     */
    public function run() {
        echo '<div style="display: block;
            margin-left: auto;
            margin-right: auto"><div class="g-recaptcha " data-sitekey="'.Yii::app()->params['recaptcha']['sitekey'].'"></div></div>';
        $cs = Yii::app()->clientScript;        
        $cs->registerScript('recaptcha-response-'.$this->id,
            "$('#submitBtnWithRecaptcha').on('click', function(e) {                
                e.preventDefault();
                var response = grecaptcha.getResponse();  
                reCaptchaPassed = true;
                if(response)
                    $('form#fromWithRecaptcha').submit();
            });"
        );   
    }
    public function run_org() {
        echo '<div style="display: block;
            margin-left: auto;
            margin-right: auto"><div class="g-recaptcha " data-sitekey="'.Yii::app()->params['recaptcha']['sitekey'].'"></div></div>';
        $cs = Yii::app()->clientScript;        
        $cs->registerScript('recaptcha-response-'.$this->id,
            "var reCaptchaPassed = false;
            $('#submitBtnWithRecaptcha').on('click', function(e) {
                if(reCaptchaPassed==false)            
                {
                    e.preventDefault();
                    var response = grecaptcha.getResponse();                    
                    var reChecking = $.get(\"".$this->url."\", {
                        response: response,
                    }, 'json');
                    reChecking.done(function(data) {
                        var suk = JSON.parse(data);
                        if(suk.success==true)
                        {
                            reCaptchaPassed = true;
                            $('form#fromWithRecaptcha').submit();    
                        }
                    });
                }
            });"
        );   
    }
    
}
