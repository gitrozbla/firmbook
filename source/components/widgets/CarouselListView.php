<?php
/**
 * ListView - widget listy.
 * 
 * Dodana obsługa wyświetlania w formie karuzeli.
 * Użyte na stronie głównej.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class CarouselListView extends CListView
{
    /**
     * Czy tryb karuzeli.
     * @var boolean
     */
    public $carousel = false;
    /**
     * Opcje karuzeli (przekazane do CarouFredSel).
     * @var array
     */
    public $carouselOptions = array();
    /**
     * Tytuł.
     * @var string|null
     */
    public $title = null;
    /**
     * Tag tytułu.
     * @var string
     */
    public $titleTagName = 'h2';
    
    /**
     * Inicjuje listę.
     * Dodana obsługa trybu karuzeli.
     */
    public function init()
    {
        if ($this->carousel !== false) {
            
            // options
            $carouselOptions = array_merge(
                    array(
                        'responsive' => true,
                        'width' => '100%',
                        'height' => '300px',
                    ),
                    $this->carouselOptions
                    );
            
            // fix to make carouFredSel happy
            if (!isset($carouselOptions['items'])) {
                $carouselOptions['items'] = array();
            }
            $carouselOptions['items'] = array_merge(
                    array(
                        'height' => 210,
                    ),
                    $carouselOptions['items']
                    );
            
            //vertical full carousel fix
            $upDown = $carouselOptions['direction'] == 'up' 
                    || $carouselOptions['direction'] == 'down';
            $fullWidth = $carouselOptions['width'] == '100%'
                    || $carouselOptions['width'] == 'auto';
            if ($upDown and $fullWidth) {
                $responsiveFix = true;
                $carouselOptions['width'] = 'auto';
            }
            
            $carouselOptions['pagination'] = array(
                'container' => "#".$this->id." .carousel-pager");
            
            // fix css
            /*if (isset($this->htmlOptions['class'])) {
                $this->htmlOptions['class'] .= ' padding-top-0';
            } else {
                $this->htmlOptions['class'] = 'padding-top-0';
            }*/
            $css = 'width: '.$carouselOptions['width'].'; '
                . 'height: '.$carouselOptions['height'].'; '
                . 'overflow: hidden;';
            if (isset($this->htmlOptions['style'])) {
                $this->htmlOptions['style'] .= '; '.$css;
            } else {
                $this->htmlOptions['style'] = $css;
            }
            
            // register scripts
            $cs = Yii::app()->clientScript;
            $cs->registerScriptFile('js/vendor/carouFredSel/jquery.carouFredSel-6.2.1-packed.js');
            $cs->registerScript(
                    'carousel-'.$this->id, 
                    "$(function(){
                        var carousel = $('#".$this->id." .".$this->itemsCssClass."');
                         carousel.carouFredSel(
                            ".CJavaScript::encode($carouselOptions)."
            				/*, scroll: {
                        		pauseOnHover: true,
            				}*/
                        );".
                        (isset($responsiveFix)
                        ?   "$(window).resize(function() {
                                var parent = carousel.parent();
                                var realWidth = parent.parent().width();
                                carousel.add(parent).css('width', realWidth);
                            });"
                        :   '')
                    ."});"
                    );
        }
        
        parent::init();
    }
    
   
    /**
     * Renderuje tytuł listy.
     */
    public function renderTitle()
    {
        echo CHtml::openTag($this->titleTagName);
        echo $this->title;
        echo CHtml::closeTag($this->titleTagName);
    }
    
    /**
     * Renderuje pager dla trybu karuzeli.
     */
    public function renderCarouselPager()
    {
        echo '<ul class="carousel-pager"></ul>';
    }
}
