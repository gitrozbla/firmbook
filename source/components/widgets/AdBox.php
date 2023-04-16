<?php
/**
 * Widget reklamy.
 * 
 * Wyświetla pole z reklamą lub puste z linkiem do oferty.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class AdBox extends CWidget 
{
    /**
     * Id grupy banerów
     * @var int|null
     */
    public $groupId = null;
    /**
     * Typ pola banerowego.
     * @var string (empty, image, carousel, youtube).
     */
    public $type = 'empty';
    
    /**
     * Cache linku do oferty (tag <a>).
     * @var string|null
     */
    protected static $_promotionLink = null;
    
    /**
     * Renderuje widget.
     * W zależności od typu i ID grupy wyświetla link do oferty,
     * baner, film, karuzele bądź inne.
     */
    public function run() {
        
        $path = Yii::app()->file->filesPath;
        
        $date = date('Y-m-d');
        
        $box = AdsBox::model()->findByAttributes(array(
        		'alias'=>$this->groupId
        ));
        
        $ads = Ad::model()->findAllByAttributes(array(
            'group_id'=>$this->groupId, 
            'enabled'=>1
            ),
        	'(date_from <= :date <= date_to) or no_limit',
        	array(':date'=>$date)	
        );
        
        switch (count($ads)) {
            case 0:
                $result = Html::link(
                        Yii::t('ad', 'Advertise here').' | '.Yii::app()->name,
                        Yii::app()->createUrl('promotion/offer'),
                        array(
                            'class'=>'promoted-link promoted-empty',
                            'target'=>'_blank',
                            'rel' => 'nofollow'
                        ));
                break;
            
            case 1:
                $ad = reset($ads);
                switch($ad->type) {
                    case 'image':
                        $result = Html::image(
                                $path.'/Add/'.$ad->id.'/'.$ad->resource,
                                Yii::t('ad.alt', $ad->id, array(), 'dbMessages'),
                                //Yii::t('ad.alt', '{'.$ad->id.'}', array(), 'dbMessages'),
                                //Yii::t('ad', '{'.$ad->id.'}', array('{'.$ad->id.'}'=>$ad->alt), 'dbMessages'),
                                //Yii::t('article.content', '{'.$articleRight->alias.'}', array('{'.$articleRight->alias.'}'=>$articleRight->content), 'dbMessages');
                                //Yii::t('ad', $ad->alt, array(), 'dbMessages'),
                                array('class'=>'promoted-image'));

                        if ($ad->text) {
                            $style = $ad->text_css ? 'style="'.$ad->text_css.'"' : '';
                            $result .= '<span class="carousel-caption" '.$style.'>'.
                                        Yii::t('ad', $ad->text, array(), 'dbMessages').
                                    '</span>';
                        }

                        $result = Html::link($result, $ad->link, array(
                            'target' => '_blank',
                            'class' => 'promoted-link',
                        	'rel' => 'nofollow'	
                            ));
                        break;
                        
                    case 'youtube':
                        $result = '<iframe width="100%" src="//www.youtube.com/embed/'.$ad->resource.'" frameborder="0" allowfullscreen></iframe>';
                        if ($ad->link) {
                            if ($ad->text) {
                                $result .= Html::link(
                                        Yii::t('ad', $ad->text, array(), 'dbMessages'), 
                                        $ad->link, 
                                        array(
                                            'class'=>'promoted-more', 
                                            'target'=>'_blank',
                                        	'rel' => 'nofollow'	
                                            )
                                        );
                            } else {
                                $result .= Html::link(
                                        Yii::t('ad', 'See more...'), 
                                        $ad->link, 
                                        array(
                                            'class'=>'promoted-more', 
                                            'target'=>'_blank',
                                        	'rel' => 'nofollow'                                        		
                                            )
                                        );
                            }
                        }
                        break;
                }
                
                break;
            
            default:
                // multiple files
                $items = array();
                
                foreach($ads as $ad) {
                    switch($ad->type) {
                        case 'image':
                            $items[] = array(
                                'itemOptions' => ($ad->link
                                    ? array('href' => $ad->link, 'target'=>'_blank', 'rel' => 'nofollow')
                                    : array()
                                    ),
                                'image' => $path.'/Add/'.$ad->id.'/'.$ad->resource,
                                'caption' => $ad->text ? Yii::t('ad', $ad->text, array(), 'dbMessages') : '',
                                'captionOptions' => array(
                                    'style' => $ad->text_css,
                                    ),
                            );
                            break;

                        case 'youtube':
                            $items[] = array(
                                'itemOptions' => ($ad->link
                                    ? array('href' => $ad->link, 'target'=>'_blank', 'rel' => 'nofollow')
                                    : array()
                                    ),
                                'image' => 'http://img.youtube.com/vi/'.$ad->resource.'/0.jpg',
                                'caption' => Yii::t('ad', 'See more...'),
                                'captionOptions' => array(
                                    'style' => $ad->text_css,
                                    ),
                            );
                            break;
                    }
                }
                
                // render all in carousel
                $result = $this->widget(
                    'Carousel',
                    array(
                        'items' => $items,
                    	/*'options' => array(
                    		//'carouselOptions' => array(
                    			'direction' => 'right',
                    		//)
                    	'htmlOptions' => array(
                    			'data-slide' => 'prev'
                    					
                    	)*/	
                    ),
                    true    // return
                );
                
        }
        
        $promotionLink = self::getPromotionLink();
        
        echo '<div class="promoted"'.($box->height ? ' style="height:'.$box->height.'px"' : "").'>'.$result.$promotionLink.'</div>';
        //echo '<div class="promoted">'.$result.$promotionLink.'</div>';
    }
    
    /**
     * Zwraca link do oferty ( tag <a>).
     * @return string
     */
    protected static function getPromotionLink()
    {
        if (self::$_promotionLink === null) {
            self::$_promotionLink = Html::link(
                Yii::t('ad', 'Advertise with us'),
                Yii::app()->createUrl('promotion/offer'),
                array(
                    'class'=>'offer-link',
                    'target'=>'_blank',
                	'rel' => 'nofollow'
                    )
                );
        }
        return self::$_promotionLink;
    }
    
}
