<?php
/**
 * Widget przewijający długie linijki z tekstem.
 * 
 * Wykorzystuje text-overflow-scroll.js.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class TextOverflowScroll extends CWidget 
{
    /**
     * Tekst do przewijania.
     * @var string
     */
    public $text = '';
    
    public $animationType = 'swing';  // or carousel
    
    public $time = 5000;
    
    public $delay = 5000;
    
    /**
     * Generuje przewijającą się linijkę tekstu.
     */
    public function run() {
        echo '<div id="text-overflow-scroll-'.$this->id.'" class="text-overflow-scroll">'.$this->text.'</div>';
        
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->homeUrl.'js/text-overflow-scroll.js');
        $cs->registerScript('text-overflow-scroll-'.$this->id,
                "$('#text-overflow-scroll-".$this->id."').textOverflowScroll({"
                    . "animationType: '".$this->animationType."', "
                    . "time: ".$this->time.", "
                    . "delay: ".$this->delay
                . "});"
                );    // moved to main.js
    }
    
}
