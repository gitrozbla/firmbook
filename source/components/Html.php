<?php
/**
 * Helper generujacy kod html.
 * 
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Html extends CHtml 
{
    /**
     * Tworzy link w innym języku
     * @TODO Poprawić implementację.
     * @param string $lang Kod języka.
     * @return string Url w zmienionym języku.
     */
    public static function createMultilanguageReturnUrl($lang='pl') 
    {	
        $controller = Yii::app()->controller;
        $route = $controller->id.'/'.$controller->action->id;
        $params = $_GET;        
        
        if(isset($params['search_context']))        	
        	$params['search_context'] = Yii::t('url', 'search', array(), null, $lang);
        
        $urlManager = Yii::app()->urlManager;
        foreach ($params as $key=>$value) {
            $params[$key] = $urlManager->translateParam($key, $value, $lang);
        }
        
        $serachObj = new Search();
        $serach = $serachObj->getFromSession();

        $params = array_merge($params, array('language'=>$lang));

        if (Yii::app()->urlManager->globalRouteMode) {
	        // nie używać tutaj Controller::createGlobalRouteUrl()
			// bo popsuje się menedżer uprawnień!
			$c = Yii::app()->controller;
			if($route==='')
				$route=$c->getId().'/'.$c->getAction()->getId();
			elseif(strpos($route,'/')===false)
				$route=$c->getId().'/'.$route;
			if ($route[0] === '/' && strlen($route) > 1) {
				$route = substr($route, 1);
			}

			if($route == 'site/index' || $route == 'categories/show')
				return $serach->createUrl(null, array_merge($params, array('language'=>$lang, Search::getContextUrlType($serach->type).'_context'=>Search::getContextUrlAction($serach->action, $serach->type))));
// 				return Yii::app()->getUrlManager()->createUrl($route,
// 					array_merge($params, array('language'=>$lang, Search::getContextUrlType($serach->type).'_context'=>Search::getContextUrlAction($serach->action, $serach->type))), '&', 'all');
			else
				return Yii::app()->getUrlManager()->createUrl($route,
						array_merge($params, array('language'=>$lang)), '&', 'all');
// 			$params = array_merge($params, array('language'=>$lang));
		} else {
// 			return $controller->createUrl($route,
// 				array_merge($params, array('language'=>$lang)));
			if($route == 'site/index' || $route == 'categories/show') {
				return $serach->createUrl(null, array_merge($params, array('language'=>$lang, Search::getContextUrlType($serach->type).'_context'=>Search::getContextUrlAction($serach->action, $serach->type))));
// 				return Yii::app()->getUrlManager()->createUrl($route,
// 					array_merge($params, array('language'=>$lang, Search::getContextUrlType($serach->type).'_context'=>Search::getContextUrlAction($serach->action, $serach->type))));
			} else
				return $controller->createUrl($route,
					array_merge($params, array('language'=>$lang)));			
		}		
    }
    
    /**
     * Funkcja używana w array_map w celu 
     * wygenerowania listy języków z flagami.
     * @param string $index Indeks w postacji kodu języka.
     * @param string $value Wartość w postaci tekstu języka.
     * @return array Tablica parametrów dla TbMenu::items.
     */
    public static function languageMap($index, $value) 
    {
    	return array(
            'label' => '<img class="lang-img" src="'
                . Yii::app()->baseUrl.'/images/flag_icons/'
                . $index.'.gif" alt="">&nbsp;'.$value.'</span>', 
            'url' => Html::createMultilanguageReturnUrl($index),
        );
    }
    
    /**
     * Generuje specjalny link typu ajax.
     * @param string $text Tekst linku.
     * @param string $url Url.
     * @param array $htmlOptions Atrybuty tagu a.
     * @return string Wygenerowany link.
     */
    public static function coolAjaxLink($text, $url='#', $htmlOptions=array() ) 
    {
        if (isset($htmlOptions['class'])) {
            $htmlOptions['class'] .= ' cool-ajax';
        } else {
            $htmlOptions['class'] = 'cool-ajax';
        }
        
        return self::link($text, $url, $htmlOptions);
    }
	
    public static function skypeWidget($skypeId, $showIcon=false) {
        $icon = $showIcon ? '<i class="fa fa-skype"></i>&nbsp;' : '';
        return $skypeId ? Html::link($icon.$skypeId, 'skype:'.$skypeId.'?call') : null;
    }

    public static function adminEmailLink($address, $showIcon=false) {
        $icon = $showIcon ? '<i class="fa fa-envelope"></i>&nbsp;' : '';
        return $address ? Html::link($icon.$address, Yii::app()->urlManager->createUrl('/admin/sendMail', array('to'=>$address))) : null;
    }
    
    public static function embedYoutube($id)
    {
        return '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$id.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    }        
}