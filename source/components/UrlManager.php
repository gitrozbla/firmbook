<?php
/**
 * Zarządzanie linkami, tworzenie i parsowanie.
 *
 * W dużym stopniu zmienione podstawowe funkcjedla obsługi tłumaczonych url.
 *
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class UrlManager extends CUrlManager
{
    /**
     * Parametry ignorowane w tłumaczeniu.
     * Dotyczy tylko tłumaczenia kluczy parametrów
     * (bo parametry tłumaczone są w kontrolerach).
     * Przechowywanie wartości w formie kluczy
     * znacząco przyspiesza przeszukiwanie tablicy.
     * @var array
     */
    protected $paramsTranslationIgnore = array(
        'language' => 1, // języki (parametr zawsze po angielsku)
        'coolAjaxRequest' => 1, // coolAjax
        'ajax' => 1, // ajax
        '_' => 1, // captcha
        'v' => 1, // captcha
        'progress_bar_id' => 1, // widget progress bar
        'refresh' => 1, // captcha i inne
        'id' => 1, // id (różne)
        'ip' => 1,
        '' => 1,
        'min' => 1, // minify controller
        'serve' => 1, // minify controller
        'lm' => 1, // minify controller
        'g' => 1, // minify controller
        't' => 1, // time (strony omijajace cache)
        'creators_page' => 1, // strony creators nie wspieraja obecnie wielu jezykow
    );

    protected $registeredParamsToTranslate = array();

    /**
     * Cache Wybrany język (kod).
     * @var string|null
     */
    protected $_language = null;

    // Potrzebne w Html::createMultilanguageReturnUrl()
    public $globalRouteMode = false;

    /**
     * Tworzy link.
     *
     * Musi być wykorzystywane praktycznie wszędzie w widokach.
     * W inteligentny sposób tłumaczy cały url.
     * Wykorzystuje do tego również tłumaczenia z modułów.
     * Do tego dopisuje do linku język, jeżeli nie jest ustawiony domyślny.
     * Do url dopisywany jest '_', gdyż urle nie zaczynające się od '_' są
     * aliasami do firm.
     *
     * @todo Dodać obsługę działania aliasów i subdomen z nazwą firmy.
     *
     * @param string $route Ścieżka (np.' kontroler/akcja').
     * @param array $params parametry get ('nazwa'=>'wartość').
     * @param string $ampersand Znak separujący ścieżkę i parametry, zmieniany przy przyjaznych linkach.
     *
     * @return string Utworzony link url.
     */
    public function createUrl($route, $params = array(), $ampersand = '&', $service=null)
    {
//        echo '<br><br>>>>>>>>>>>>>>>>>>>>>>>>>>';
//        echo '<br>UrlManager->createUrl(): ';
        // dodajemy parametr języka - dla lepszego SEO
        // jeśli nie ma języka w url
        if (!isset($params['language'])) {
            if (is_a(Yii::app(), 'WebApplication')
				&& Yii::app()->user->hasState('language'))
                Yii::app()->language = Yii::app()->user->getState('language');
            else if (isset(Yii::app()->request->cookies['language']))
                Yii::app()->language = Yii::app()->request->cookies['language']->value;
            // jeżeli użytkownik korzysta z domyślnego języka to nie dodajemy do url nic
            if (Yii::app()->language != Yii::app()->params['defaultLanguage']) {
                $params['language'] = Yii::app()->language;
            }
            $language=  Yii::app()->language;
        } else {
            $language = $params['language'];
            if ($params['language'] == Yii::app()->params['defaultLanguage']) {
                unset($params['language']);
            }
        }

        $creatorsMode =  (Yii::app()->params['websiteMode'] == 'creators') ? true : false;
        $globalRouteMode = ($creatorsMode && $service == 'all') ? true : false;

        if ($globalRouteMode) {
            unset($params['creatorsHash']);
        }
        /*if ($globalRouteMode) {
                // Akcja globalnego kontrolera z poziomu Creators.
                $toHash = (Yii::app()->params['key']['systemSalt']) . '/' . $route;
                var_dump($toHash);
                $params['creatorsHash'] = substr(md5($toHash), 0, 10);
        }*/

        if (($route == 'companies/show'
            || $route == 'products/show'
            || $route == 'services/show')
            &&
            (isset($params['name']) || isset($params['subdomain']))
            &&
            (!$globalRouteMode)) 
        {
            $name = $params['name'] = isset($params['name']) ? $params['name'] : $params['subdomain'];
            $language = isset($params['language']) ? $params['language'].'/' : '';
            unset($params['subdomain']);
            if ($creatorsMode == true && $service == 'all') {
                    $result = parent::createUrl($route, $params);
            } else {
                    //unset($params['name']);
                    //unset($params['language']);
                    $result = parent::createUrl($language.$name); //, $params);
            }
        } else {
	        // creators
            if ($creatorsMode && $service != 'all' && substr($route, 0, 9) == 'creators/') {
                $route = substr($route, 9);
            }

            $r = explode('/', $route);

            if (!$creatorsMode) {
                if($r[0]=='companies' || $r[0]=='products' || $r[0]=='services')
                    $r = array_merge(['c'], $r);
//                  $r = array('c')+$r[0];
            }

            if ($language != Yii::app()->sourceLanguage) {
                /////////////////////////////
                // moduł, kontroler, akcja //
                /////////////////////////////
                $modules = Yii::app()->getModules();

                // creators support
                if ($creatorsMode && $service==null) {
                    $messagesPath = 'CreatorsModule.';
                    $counter = 1;
                } else {
                    $messagesPath = '';
                    $counter = 0;
                }

	            // start - zmiana urli
                if($route == 'categories/show' || $route == 'site/index')
                {	
                } else {
                    foreach ($r as $key => $value) 
                    {
                        // tłumaczenie...
                        if ($counter == 0 and isset($modules[$value])) {
//                            $messagesPath .= $value . 'Module.';
                            $messagesPath .= ucfirst($value) . 'Module.';
                        } else if (!empty($value)) {
                            $counter++;
                        }
                        if ($value != '' and !isset($this->paramsTranslationIgnore[$value])/* and ($counter < 2 or !($counter&1)) */)
                        {
                            $r[$key] = Yii::t($messagesPath . 'url', $value, array(), null, $language);
                        }
                        // jeżeli istnieje taki moduł, to tłumeczenie pochodzi z modułu
                    }
                }
                
                ///////////////////
                // parametry GET //
                ///////////////////
                $messagesPathWithUrl = $messagesPath . 'url';
                $messageSource = Yii::app()->messages;
                if (!isset($params['ajax'])) {
                    $newParams = array();
                    foreach ($params as $key => $value) {
                        if (isset($this->paramsTranslationIgnore[$key]) == false) {
                            // tłumaczenie klucza
                            $newKey = $this->translateKey($key, $messagesPathWithUrl, $language, false);
                            if ($messageSource->translateCategoryExists($messagesPath . $key, $language)) {
                                // tłumaczymy wartość
                                $newParams[$newKey] = Yii::t($messagesPath . $key, $value, array(), null, $language);
                            } else {
                                // nie tłumaczymy wartości
                                $newParams[$newKey] = $value;
                            }
                        } else {
                            $newParams[$key] = $value;
                        }
                    }
                    $params = $newParams;
                }
            }

            $route = implode('/', $r);
            
            if (!$creatorsMode || $service == 'firmbook' || ($creatorsMode && $service == 'all')) {
                            // Firmbook lub akcja globalnego kontrolera z poziomu Creators
                // add '_'
                if (empty($route)) {
                    // wymagana poprawna ścieżka kontroler/akcja
                                    //$route = Yii::app()->controller->id . '/' . Yii::app()->controller->action->id;
                } else {
                    $route = '_'.$route;
                }
            }
            
            // linki z parametrem ajax zawsze mają parametry w formie get
            if (isset($params['ajax'])) {
                $appendParams = $this->appendParams;
                $this->appendParams = false;
                $result = parent::createUrl($route, $params, $ampersand);
                $this->appendParams = $appendParams;
            } else {
                $result = parent::createUrl($route, $params, $ampersand);
            }

            if ($creatorsMode && $service == 'firmbook') {
                $result = (Yii::app()->params['firmbookUrl']) . $result;
            } elseif (!$creatorsMode && $service == 'creators') {
                $result = (Yii::app()->params['creatorsUrl']) . $result;
            }
        }

        // forma coolAjax
        /* if (Yii::app()->params['ajax']) {
          $params += array(
          '#' => $route
          );
          $route = '';
        } */

        if ($globalRouteMode) {
            $uri = $result;
            if (strpos($uri, '?') !== false) $uri = substr($uri, 0, strpos($uri, '?'));
            $toHash = (Yii::app()->params['key']['systemSalt']) . ':' . $uri;
            //var_dump($toHash);
            $merge = (strpos($result, '?') !== false) ? '&' : '?';
            //$result = trim($result, '/');	// Chyba nie
            $result .= $merge .'creatorsHash=' . substr(md5($toHash), 0, 10);
        }
        
//        echo '<br>$result: '.$result;
//        echo '<br><<<<<<<<<<<<<<<<';
        return $result;
    }    

    /*
     * Użyta w HttpRequest w celu obejścia walidacji tokena CSRF, np. DOTPAY
     */
    public function parseUrlParent($request)
    {
    	return parent::parseUrl($request);
    }

    /**
     * Parsuje url.
     *
     * Wykrywa język, dokonuje tłumaczenia.
     * Jeśli url nie zaczyna się od '_' to jest to alias do firmy.
     *
     * @todo Dodać obsługę alias i subdomeny z nazwą firmy.
     *
     * @param string $request Url do sparsowania.
     *
     * @return string Ścieżka obsługiwana przez YII ('moduł/kontroler/akcja/parametry...').
     */
    public function parseUrl($request)
    {
        // sprawdzamy, czy url nie jest subdomena
        if(isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } else {
            $host = $_SERVER['SERVER_NAME'];
        }
        $baseUrl = $request->getBaseUrl();
        $hostInfo = $request->getHostInfo();

        if (!$request->getIsSecureConnection() || $hostInfo != 'https://'.$host) {    
            Yii::app()->request->redirect(
//                        'https://www.'.$host.
                    $hostInfo.
                    $request->getRequestUri(),
                    301);
            exit();
        } else {
            // parsujemy url
            $route = parent::parseUrl($request);
        }

        $creatorsMode = Yii::app()->params['websiteMode'] == 'creators';

        // sprawdzamy, czy parametr języka na pewno został wycięty ze ścieżki (chyba coś jest nie tak z regułami url)
        /*if (Func::startsWith($route, $_GET['language'].'/')) {
            $len = strlen($_GET['language']);
            if (count($route) == $len or isset($route[$len]) and $route[$len] == '/') {
                $route = substr($route, $len + 1);
            }
        }*/

        // po sparsowaniu powinien się pojawić parametr _GET['language']
        // (o ile url go posiadał)
        // oraz pozostałe parametry, które należy przetłumaczyć.
        $language = $this->retrieveLanguage();

        // sprawdzanie aliasow
        if (!empty($route) && $route[0] == '_') {
            // Zwykły url lub akcja globalnego kontrolera z poziomu Creators.
            $route = substr($route, 1);
            $this->globalRouteMode = true;

            if ($creatorsMode) {
                // Zabezpieczenie przed niedozwolonymi adresami
                $uri = $request->getRequestUri();
                //$uri = trim($uri, '/');	// Chyba nie
                if (strpos($uri, '?') !== false) $uri = substr($uri, 0, strpos($uri, '?'));
                $toHash = (Yii::app()->params['key']['systemSalt']) . ':' . $uri;//$route;
                //var_dump($toHash);
                if ($_GET['creatorsHash'] != substr(md5($toHash), 0, 10)) {
                        throw new CHttpException(403, 'Incorrect hash.');
                }
                /*$found = false;
                foreach($this->globalRouteModeAllowetRoutes as $allowed) {
                        if (Func::startsWith($route, $allowed)) {
                                $found = true;
                                break;
                        }
                }
                if (!$found) {
                        //throw new CHttpException(404, 'Incorrect URL address.');
                }*/
            } else {
                // Sprawdzamy czy route zaczyna się od _c, co oznacza obejście pokrywania się takich tras 
                // jak _companies/contex-action z _companies/add co odpala kontrole Site lub Categories a nie Companies
                if (!empty($route) && strlen($route) > 1 && $route[0] == 'c' && $route[1] == '/') {
                        $route = substr($route, 2);
                }  
            }
        } else {
            if ($creatorsMode) {
                // Jest to wewnętrzny link Creators
                if (substr($route, 0, 9) != 'creators/') {
                            // creators route
                    $route = 'creators/'.$route;
                }
            } else {
                // Alias product/service/company
                if (!empty($route)) {
                    if (strlen($route) > 3 && $route[2] == '/') {
                        // przy aliasie reguły url nie zadziałają
                        // i trzeba wyciąć język ręcznie
                        $language = substr($route, 0, 2);
                        $_GET['language'] = $language;
                        $this->retrieveLanguage();

                        $route = substr($route, 3);
                        $prefix = $language.'/';
                    } else {
                        $prefix = '';
                    }

                    $itemType = Yii::app()->db->createCommand()
                            ->select('cache_type')
                            ->from('tbl_item')
                            ->where('alias=:alias', array(':alias'=>$route))
                            ->queryScalar();
                    if (empty($itemType)) {
                        throw new CHttpException(404, Yii::t('item', 'Object does not exist.'));
                    }
                    // alias do firmy, produktu, lub usługi
                    $controllers = array(
                        'p' => 'products',
                        's' => 'services',
                        'c' => 'companies',
                    );

                    return $controllers[$itemType].'/show/name/'.$route;
                }
            }
        }


        /*if (Yii::app()->request->isAjaxRequest) {
            return $route;
        }*/

        /*if (Yii::app()->request->getParam('ajax')) {
            return $route;
        }*/

        // teraz mamy już na pewno ścieżkę w postaci 'cośtam/cośtam...'
        if ($language != Yii::app()->sourceLanguage) {
            /////////////////////////////
            // moduł, kontroler, akcja //
            /////////////////////////////
            $r = explode('/', $route);
            $modules = Yii::app()->getModules();
            $messagesPath = 'inv.';
            // licznik counter przelicza ile fragmentów ścieżki przetłumaczyć.
            // gdy counter==0 to parsujemy moduł lub kontroler;
            // gdy counter==1 to parsujemy akcję;
            // gdy counter==2 to doszliśmy do parametrów get, więc pomijamy tłumaczenie cyfr
            // tutaj cache, aby nie wczytywać wszystkich modułów w poszukiwaniu tłumaczenia
            $modulesTranslationsInv = (Yii::app()->cache->get('UrlManager.modulesTranslationsInv'));
            if ($modulesTranslationsInv === false) {

                $modulesTranslations = array();
                foreach ($modules as $moduleName => $module) {
                    if ($moduleName != 'gii' and $moduleName != 'rights') {
                        // importowanie!
                        Yii::import('application.modules.' . $module['class'], true);
                        $modulesTranslations[$moduleName] = Yii::t($moduleName . 'Module.url', $moduleName);
                    }
                }
                $modulesTranslationsInv = array_flip($modulesTranslations);
                //$dependency = new CDirectoryCacheDependency(Yii::app()->basePath.'/modules');	// te zależności raczej się nie przydadzą
                //$dependency->recursiveLevel = 1;
                Yii::app()->cache->set('UrlManager.modulesTranslationsInv', $modulesTranslationsInv, 0 /* , $dependency */);
            }

            $counter = 0;
            $messageSource = Yii::app()->messages;
            $language = Yii::app()->language;
            foreach ($r as $i => $routePart) {
                // tłumaczenie...
                if ($routePart != '') {
                    if ($counter < 2) { // czy moduł, kontroler lub akcja
                        if (!isset($this->paramsTranslationIgnore[$routePart])) {
                            if ($counter == 0 and isset($modulesTranslationsInv[$routePart])) { // jeżeli istnieje taki moduł, to tłumaczenie pochodzi z modułu
                                $r[$i] = $modulesTranslationsInv[$routePart];
                                $messagesPath .= $r[$i] . 'Module.';
                                // wymuszamy wcześniejsze importowanie klasy modułu,
                                // żeby można było pobrać z niej tłumaczenie
                                Yii::import('application.modules.' . ($modules[$r[$i]]['class']), true);
                                $counter = -1; // zostanie zinkrementowane na 0
                            } else { // to już jest kontroler albo akcja
                                $r[$i] = Yii::t($messagesPath . 'url', $routePart);
                            }
                        }
                    } else if ($counter % 2 == 0) { // klucz parametru
                        if ($counter == 2) { // pierwszy klucz
                            // uzupełniamy messagesPath o url i zaznaczamy, że już to zrobiono
                            $messagesPathWithUrl = $messagesPath . 'url';

                            // potrzebujemy parametr ajax, aby określić czy w ogóle tłumaczyć parametry
                            // czy znajduje się on w get?
                            if (isset($_GET['ajax'])) {
                                $ignoreAllParamsTranslation = true;
                                //break;	// nie przerywamy tłumaczenia dla path, a jedynie dla get
                            }
                            // a może znajduje się w route...
                            $ajaxPos = array_search('ajax', $r);
                            if ($ajaxPos != false) { // znaleziono ajax
                                // tutaj sprawdzamy czy słowo 'ajax' jest kluczem parametru
                                if (($ajaxPos - $i) % 2 == 0) {
                                    $ignoreAllParamsTranslation = true;
                                    //break;	// nie przerywamy tłumaczenia dla path, a jedynie dla get
                                }
                            }
                        }

                        $translateCurrent = false;
                        $lastKey = $routePart;
                        if (isset($this->paramsTranslationIgnore[$routePart]) == false) {
                            // parametry z paramsTranslationIgnore są pominięte
                            $r[$i] = $lastKey = $this->translateKey($routePart, $messagesPathWithUrl, null, true);
                        }
                    } else { // wartość parametru
                        if ($messageSource->translateCategoryExists($messagesPath . $lastKey, $language)) {
                            $r[$i] = Yii::t($messagesPath . $lastKey, $routePart);
                        }
                    }
                }
                $counter++;
            }
            $route = implode('/', $r);

			// Creators - allowed global routes
			//var_dump($route);
			//isset($this->creatorsAllowedGlobalRoutes[$route])*/

            //var_dump($route);	// debug
            //var_dump($_GET);	// debug
            ///////////////////
            // parametry GET //
            ///////////////////
            // nie tłumaczymy niczego, jeżeli wykryto parametr ajax (wyżej)
            if (!isset($ignoreAllParamsTranslation)) {
                $messagesPathWithUrl = $messagesPath . 'url';
                $language = Yii::app()->language;
                foreach ($_GET as $key => $value) {
                    if (isset($this->paramsTranslationIgnore[$key]) == false) {
                        // parametry z paramsTranslationIgnore są pominięte
                        unset($_GET[$key]);
                        $newKey = $this->translateKey($key, $messagesPathWithUrl, null, true);
                        if (!is_array($value) && $messageSource->translateCategoryExists($messagesPath . $newKey, $language)) {
                            $_GET[$newKey] = Yii::t($messagesPath . $newKey, $value);
                        } else {
                            $_GET[$newKey] = $value;
                        }
                    }
                }
            }
            unset($_GET['language']);
        }
        
        if($route == 'categories/show' || $route == 'site/index') {
        	if(isset($_GET['companies_context'])) {
        		$_GET['companies_context'] = Yii::t('inv.url', $_GET['companies_context']);
        		if($_GET['companies_context'] == 'buying')
        			$_GET['context'] = Search::getContextOption('buy', 'company');
        		elseif($_GET['companies_context'] == 'selling')
        			$_GET['context'] = Search::getContextOption('sell', 'company');
        		unset($_GET['companies_context']);
        	} elseif(isset($_GET['products_context'])) {
        		$_GET['products_context'] = Yii::t('inv.url', $_GET['products_context']);
        		if($_GET['products_context'] == 'to-buy')
        			$_GET['context'] = Search::getContextOption('buy', 'product');
        		elseif($_GET['products_context'] == 'to-sell')
        			$_GET['context'] = Search::getContextOption('sell', 'product');
        		unset($_GET['products_context']);
        	} elseif(isset($_GET['services_context'])) {
        		$_GET['services_context'] = Yii::t('inv.url', $_GET['services_context']);
        		if($_GET['services_context'] == 'requests')
        			$_GET['context'] = Search::getContextOption('buy', 'service');
        		elseif($_GET['services_context'] == 'offers')
        			$_GET['context'] = Search::getContextOption('sell', 'service');
        		unset($_GET['services_context']);
            }
        						 
        }
        
//         var_dump($route);	// debug
//         var_dump($_GET);	// debug
        
        return $route;
    }

    /**
     * Funkcja tłumaczy klucze parametrów podanych w url.
     * @param string $key Klucz.
     * @param string $category Kategoria tłumaczeń.
     * @param string|null $language Kod języka.
     * @param boolean $invert Czy tłumaczenie odwrotne.
     * @return string Przedłumaczony klucz.
     */
    protected function translateKey($key, $category, $language = null, $invert = false)
    {    	
        $inv = $invert ? 'inv.' : '';

        // tłumaczymy klucz
        $translated = false;
        $postfixes = Yii::app()->messages->getTranslations($inv . 'postfix', $language);        
        foreach ($postfixes as $postfix => $translation) {
            if (Func::endsWith($key, $postfix)) {
                // pierwsza część
                $baseKeyTranslation = Yii::t($category, substr($key, 0, -strlen($postfix)), array(), null, $language);
                // druga część
                $postfixKeyTranslation = $translation;
                // razem
                $key = $baseKeyTranslation . $postfixKeyTranslation;
                $translated = true;
                break;
            }
        }
        if ($translated == false) {
            // zwykłe tłumaczenie
            $key = Yii::t($category, $key, array(), null, $language);
        }

        return $key;
    }


    /**
     * Przekształca subdomenę lub alias firmy na docelowy adres
     * zawierający kontroler, akcję i parametry.
     * @param string $subdomain Subdomena lub alias firmy.
     * @return string Adres docelowy.
     */
    public function parseSubdomain($subdomain)
    {
    	// test blazeja
    	return $subdomain;
        //return '/company/show/name/'.$subdomain;
    }

    /**
     * Generuje reguły parsowania url uwzględniając ich tłumaczenie.
     * Magia.
     */
    /*
     * ic: wyłączona z użycia ponieważ koliduje z subdomenami i właściwie nie została skończona i użyta
     */
    protected function processRules()
    {
        //$language = $this->retrieveLanguage();
        $newRules = array();
        $languages = '<language:('.implode('|', array_keys(Yii::app()->params->languages)).')>/';
        $translate = Yii::app()->language != Yii::app()->sourceLanguage;
        
        // change language on index
        $newRules[$languages] = '';
        // convert rules
        foreach($this->rules as $key=>$value) {/*var_dump($key.' '.$value);*/
        	//warunek na https prawdopodobnie wymaga dopracowania
            if ($translate && (substr($key, 0, 8) != 'https://')) {
            	
            	$newRules[$languages.$key] = $value;            	
            	$no_param_translation = false;
 
            	if(is_array($value))
            	{
            		if(array_key_exists('defaultParams', $value) 
            			&& array_key_exists('no_params_translate', $value['defaultParams']) && $value['defaultParams']['no_params_translate'])
            			$no_param_translation = true;            			
            	}
                // key translate
                $keyParts = explode('/', $key);
                $keyParts2 = array();
                foreach($keyParts as $partKey=>$partValue) {
                    if ($partValue[0] == '<') {
                    	$subParts = explode(':', $partValue);
                    	if(!$no_param_translation 
                    		&& 
                    		!(is_array($value) && array_key_exists('defaultParams', $value) 
            					&& array_key_exists('no_param_translate', $value['defaultParams'])
                    			&& in_array(substr($subParts[0], 1), $value['defaultParams']['no_param_translate'])
                    		)
                    	) {
	                        $keyParts2[] =
	                        	'<'.Yii::t('url', substr($subParts[0], 1)).':'
	                        	.($subParts[0] == '<search_context' ? '('.Yii::t('url', substr($subParts[1], 1, -2)).')>' : $subParts[1]);
                    	} else {
                    		$keyParts2[] = $partValue;
                    	}
                    } else {
                    	if ($partValue[0] == '_') {
                    		$keyParts2[] = '_'.Yii::t('url', substr($partValue, 1));
                    	} else
                        	$keyParts2[] = Yii::t('url', $partValue);
                    }
                }
                
                $key = implode('/', $keyParts2);
            }

            if(substr($key, 0, 8) == 'https://') {
            	$newRules[$key.'/'.$languages] = $value;
            	$newRules[$key] = $value;
            } else {
//             	$newRules[$languages.$key] = $value;
            	$newRules[$key] = $value;
            }
        }
        
        // to nie działa dobrze
        
        $newRules[$languages . '_c/<controller:\w+>/<action:\w+>/*'] = '_c/<controller>/<action>';
        $newRules[$languages . '_c/<controller:\w+>/*'] = '_c/<controller>';
        
//         $newRules[$languages . '_c/<controller:\w+>/<action:\w+>/*'] = '<controller>/<action>';
//         $newRules[$languages . '_c/<controller:\w+>/*'] = '<controller>';
        
        // default change language rules
        //$newRules[$languages . '<module:\w+>/<controller:\w+>/<action:\w+>/*'] = '<module>/<controller>/<action>';
        $newRules[$languages . '<controller:\w+>/<action:\w+>/*'] = '<controller>/<action>';
        $newRules[$languages . '<controller:\w+>/*'] = '<controller>';

        if (Yii::app()->params['websiteMode'] == 'creators') {
            foreach($newRules as $key=>$value) {
                /*$newRules['_creators-edit/<creatorsHash:\w+>/' . $languages . '<controller:\w+>/<action:\w+>/*'] = '<controller>/<action>';
                $newRules['_creators-edit/<creatorsHash:\w+>/' . $languages . '<controller:\w+>/*'] = '<controller>';*/
                $newRules['_creators-edit/<creatorsHash:\w+>/' . $key] = $value;
            }
        }

        $this->rules = $newRules;
        
//        var_dump($this->rules);
//        foreach($this->rules as $key => $rule)
//        {  
//            echo '<br>'.$key;
//            if(is_array($rule))
//            {
//                foreach($rule as $r)
//                {
//                    echo '<br>'.$r;
//                }
//            } else
//                echo '<br>'.$rule;
//            echo '<br>';
//        }   
           
        
        parent::processRules();
    }    

    /**
     * Funkcja sprawdza czy w _GET/_POST podano język i ustawia go.
     * @return string Kod obecnego języka.
     */
    public function retrieveLanguage()
    {
        if ($this->_language !== null) {
            return $this->_language;
        }
        ///////////
        // JĘZYK //
        ///////////
        // oryginalnie było to w tutorialu w klasie Controller,
        // ale obiekt kontrolera jest tworzony dopiero później
        // a my potrzebujemy języka już teraz

        // convert uri part to get
        $uri = Yii::app()->request->requestUri;
        $requestPart = substr($uri, 1, strpos($uri, '/', 1)-1);
        if (isset(Yii::app()->params['languages'][$requestPart])) {
            $_GET['language'] = $requestPart;
        }

        if (isset($_GET['language'])) {
            // wybrano język przez GET
            Yii::app()->language = $_GET['language'];
            Yii::app()->user->setState('language', $_GET['language']);

            // ciasteczko powinno być aktualizowane po każdej zmianie języka
            $cookie = new CHttpCookie('language', $_GET['language']);
            $cookie->expire = time() + (60 * 60 * 24 * 365); // (1 rok)
            Yii::app()->request->cookies['language'] = $cookie;

        } else  if (isset($_POST['language'])) {
            // wybrano język przez POST
            $lang = $_POST['language'];
            $this->redirect($_POST[$lang]);
        } else if (Yii::app()->request->url == '') {
            // język z sesji
            if (Yii::app()->user->hasState('language'))
                Yii::app()->language = Yii::app()->user->getState('language');
            // język z ciastka
            else if (isset(Yii::app()->request->cookies['language']))
                Yii::app()->language = Yii::app()->request->cookies['language']->value;
        } else {
            Yii::app()->user->setState('language', Yii::app()->params['defaultLanguage']);
        }

        // ciasteczko powinno być aktualizowane po każdej zmianie języka
        if (isset(Yii::app()->request->cookies['language'])
        		&& Yii::app()->language != Yii::app()->request->cookies['language']->value)
        {
        	$cookie = new CHttpCookie('language', Yii::app()->language);
        	$cookie->expire = time() + (60 * 60 * 24 * 365); // (1 rok)
        	Yii::app()->request->cookies['language'] = $cookie;
        }


        return Yii::app()->language;
    }

    public function registerParamToTranslate($param, $function)
    {
        $this->registeredParamsToTranslate[$param] = $function;
    }

    public function translateParam($param, $value, $lang)
    {
        if (isset($this->registeredParamsToTranslate[$param])) {
            $function = $this->registeredParamsToTranslate[$param];
            return call_user_func($function, $param, $value, $lang);
        } else {
            return $value;
        }
    }

    public function parseUrl_http_notused($request)
    {
//     	echo '<br>UrlManager->parseUrl()';
        // sprawdzamy, czy url nie jest subdomena
        if(isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } else {
            $host = $_SERVER['SERVER_NAME'];
        }
        $baseUrl = $request->getBaseUrl();
        $hostInfo = $request->getHostInfo();

        if ($hostInfo != 'http://'.$host) {
            if (substr($hostInfo, 7, 4) == 'www.'
                    && 'http://www.'.$host == $hostInfo) {
                // prefix with www.
                Yii::app()->request->redirect(
                        'http://www.'.$host.
                        $request->getRequestUri(),
                        301);
                exit();
			} /*else if (substr($hostInfo, -(strlen($host))) != $host) {
				// not primary domain
                Yii::app()->request->redirect(
                        $hostInfo.
                        $request->getRequestUri(),
                        301);
				exit();
            }*/ else {
                $domain = substr($hostInfo, 7);
                $subdomain = substr($host, 0, -strlen($domain)-1);
                $route = $this->parseSubdomain($subdomain);
            }
        } else {
            // parsujemy url
            $route = parent::parseUrl($request);
        }

		$creatorsMode = Yii::app()->params['websiteMode'] == 'creators';

        // sprawdzamy, czy parametr języka na pewno został wycięty ze ścieżki (chyba coś jest nie tak z regułami url)
        /*if (Func::startsWith($route, $_GET['language'].'/')) {
            $len = strlen($_GET['language']);
            if (count($route) == $len or isset($route[$len]) and $route[$len] == '/') {
                $route = substr($route, $len + 1);
            }
        }*/

        // po sparsowaniu powinien się pojawić parametr _GET['language']
        // (o ile url go posiadał)
        // oraz pozostałe parametry, które należy przetłumaczyć.
        $language = $this->retrieveLanguage();

        // sprawdzanie aliasow
        if (!empty($route) && $route[0] == '_') {
			// Zwykły url lub akcja globalnego kontrolera z poziomu Creators.
			$route = substr($route, 1);
			$this->globalRouteMode = true;

			if ($creatorsMode) {
				// Zabezpieczenie przed niedozwolonymi adresami
				$uri = $request->getRequestUri();
				//$uri = trim($uri, '/');	// Chyba nie
				if (strpos($uri, '?') !== false) $uri = substr($uri, 0, strpos($uri, '?'));
				$toHash = (Yii::app()->params['key']['systemSalt']) . ':' . $uri;//$route;
				//var_dump($toHash);
				if ($_GET['creatorsHash'] != substr(md5($toHash), 0, 10)) {
					throw new CHttpException(403, 'Incorrect hash.');
				}
				/*$found = false;
				foreach($this->globalRouteModeAllowetRoutes as $allowed) {
					if (Func::startsWith($route, $allowed)) {
						$found = true;
						break;
					}
				}
				if (!$found) {
					//throw new CHttpException(404, 'Incorrect URL address.');
				}*/
			} else {
				// Sprawdzamy czy route zaczyna się od _c, co oznacza obejście pokrywania się takich tras 
				// jak _companies/contex-action z _companies/add co odpala kontrole Site lub Categories a nie Companies
				if (!empty($route) && strlen($route) > 1 && $route[0] == 'c' && $route[1] == '/') {
					$route = substr($route, 2);
				}  
			}
		} else {
			if ($creatorsMode) {
				// Jest to wewnętrzny link Creators
				if (substr($route, 0, 9) != 'creators/') {
					// creators route
		            $route = 'creators/'.$route;
				}
			} else {
              // Alias product/service/company
				if (!empty($route)) {
	                if (strlen($route) > 3 && $route[2] == '/') {
	                    // przy aliasie reguły url nie zadziałają
	                    // i trzeba wyciąć język ręcznie
	                    $language = substr($route, 0, 2);
	                    $_GET['language'] = $language;
	                    $this->retrieveLanguage();
	
	                    $route = substr($route, 3);
	                    $prefix = $language.'/';
	                } else {
	                    $prefix = '';
	                }
	
	                $itemType = Yii::app()->db->createCommand()
	                        ->select('cache_type')
	                        ->from('tbl_item')
	                        ->where('alias=:alias', array(':alias'=>$route))
	                        ->queryScalar();
	                if (empty($itemType)) {
	                    throw new CHttpException(404, Yii::t('item', 'Object does not exist.'));
	                }
	                // alias do firmy, produktu, lub usługi
	                $controllers = array(
	                    'p' => 'products',
	                    's' => 'services',
	                    'c' => 'companies',
	                );

	                return $controllers[$itemType].'/show/name/'.$route;
				}
			}
        }


        /*if (Yii::app()->request->isAjaxRequest) {
            return $route;
        }*/

        /*if (Yii::app()->request->getParam('ajax')) {
            return $route;
        }*/

        // teraz mamy już na pewno ścieżkę w postaci 'cośtam/cośtam...'
        if ($language != Yii::app()->sourceLanguage) {
            /////////////////////////////
            // moduł, kontroler, akcja //
            /////////////////////////////
            $r = explode('/', $route);
            $modules = Yii::app()->getModules();
            $messagesPath = 'inv.';
            // licznik counter przelicza ile fragmentów ścieżki przetłumaczyć.
            // gdy counter==0 to parsujemy moduł lub kontroler;
            // gdy counter==1 to parsujemy akcję;
            // gdy counter==2 to doszliśmy do parametrów get, więc pomijamy tłumaczenie cyfr
            // tutaj cache, aby nie wczytywać wszystkich modułów w poszukiwaniu tłumaczenia
            $modulesTranslationsInv = (Yii::app()->cache->get('UrlManager.modulesTranslationsInv'));
            if ($modulesTranslationsInv === false) {

                $modulesTranslations = array();
                foreach ($modules as $moduleName => $module) {
                    if ($moduleName != 'gii' and $moduleName != 'rights') {
                        // importowanie!
                        Yii::import('application.modules.' . $module['class'], true);
                        $modulesTranslations[$moduleName] = Yii::t($moduleName . 'Module.url', $moduleName);
                    }
                }
                $modulesTranslationsInv = array_flip($modulesTranslations);
                //$dependency = new CDirectoryCacheDependency(Yii::app()->basePath.'/modules');	// te zależności raczej się nie przydadzą
                //$dependency->recursiveLevel = 1;
                Yii::app()->cache->set('UrlManager.modulesTranslationsInv', $modulesTranslationsInv, 0 /* , $dependency */);
            }

            $counter = 0;
            $messageSource = Yii::app()->messages;
            $language = Yii::app()->language;
            foreach ($r as $i => $routePart) {
                // tłumaczenie...
                if ($routePart != '') {
                    if ($counter < 2) { // czy moduł, kontroler lub akcja
                        if (!isset($this->paramsTranslationIgnore[$routePart])) {
                            if ($counter == 0 and isset($modulesTranslationsInv[$routePart])) { // jeżeli istnieje taki moduł, to tłumaczenie pochodzi z modułu
                                $r[$i] = $modulesTranslationsInv[$routePart];
                                $messagesPath .= $r[$i] . 'Module.';
                                // wymuszamy wcześniejsze importowanie klasy modułu,
                                // żeby można było pobrać z niej tłumaczenie
                                Yii::import('application.modules.' . ($modules[$r[$i]]['class']), true);
                                $counter = -1; // zostanie zinkrementowane na 0
                            } else { // to już jest kontroler albo akcja
                                $r[$i] = Yii::t($messagesPath . 'url', $routePart);
                            }
                        }
                    } else if ($counter % 2 == 0) { // klucz parametru
                        if ($counter == 2) { // pierwszy klucz
                            // uzupełniamy messagesPath o url i zaznaczamy, że już to zrobiono
                            $messagesPathWithUrl = $messagesPath . 'url';

                            // potrzebujemy parametr ajax, aby określić czy w ogóle tłumaczyć parametry
                            // czy znajduje się on w get?
                            if (isset($_GET['ajax'])) {
                                $ignoreAllParamsTranslation = true;
                                //break;	// nie przerywamy tłumaczenia dla path, a jedynie dla get
                            }
                            // a może znajduje się w route...
                            $ajaxPos = array_search('ajax', $r);
                            if ($ajaxPos != false) { // znaleziono ajax
                                // tutaj sprawdzamy czy słowo 'ajax' jest kluczem parametru
                                if (($ajaxPos - $i) % 2 == 0) {
                                    $ignoreAllParamsTranslation = true;
                                    //break;	// nie przerywamy tłumaczenia dla path, a jedynie dla get
                                }
                            }
                        }

                        $translateCurrent = false;
                        $lastKey = $routePart;
                        if (isset($this->paramsTranslationIgnore[$routePart]) == false) {
                            // parametry z paramsTranslationIgnore są pominięte
                            $r[$i] = $lastKey = $this->translateKey($routePart, $messagesPathWithUrl, null, true);
                        }
                    } else { // wartość parametru
                        if ($messageSource->translateCategoryExists($messagesPath . $lastKey, $language)) {
                            $r[$i] = Yii::t($messagesPath . $lastKey, $routePart);
                        }
                    }
                }
                $counter++;
            }
            $route = implode('/', $r);

			// Creators - allowed global routes
			//var_dump($route);
			//isset($this->creatorsAllowedGlobalRoutes[$route])*/

            //var_dump($route);	// debug
            //var_dump($_GET);	// debug
            ///////////////////
            // parametry GET //
            ///////////////////
            // nie tłumaczymy niczego, jeżeli wykryto parametr ajax (wyżej)
            if (!isset($ignoreAllParamsTranslation)) {
                $messagesPathWithUrl = $messagesPath . 'url';
                $language = Yii::app()->language;
                foreach ($_GET as $key => $value) {
                    if (isset($this->paramsTranslationIgnore[$key]) == false) {
                        // parametry z paramsTranslationIgnore są pominięte
                        unset($_GET[$key]);
                        $newKey = $this->translateKey($key, $messagesPathWithUrl, null, true);
                        if (!is_array($value) && $messageSource->translateCategoryExists($messagesPath . $newKey, $language)) {
                            $_GET[$newKey] = Yii::t($messagesPath . $newKey, $value);
                        } else {
                            $_GET[$newKey] = $value;
                        }
                    }
                }
            }
            unset($_GET['language']);
        }
        
        if($route == 'categories/show' || $route == 'site/index') {
        	if(isset($_GET['companies_context'])) {
        		$_GET['companies_context'] = Yii::t('inv.url', $_GET['companies_context']);
        		if($_GET['companies_context'] == 'buying')
        			$_GET['context'] = Search::getContextOption('buy', 'company');
        		elseif($_GET['companies_context'] == 'selling')
        			$_GET['context'] = Search::getContextOption('sell', 'company');
        		unset($_GET['companies_context']);
        	} elseif(isset($_GET['products_context'])) {
        		$_GET['products_context'] = Yii::t('inv.url', $_GET['products_context']);
        		if($_GET['products_context'] == 'to-buy')
        			$_GET['context'] = Search::getContextOption('buy', 'product');
        		elseif($_GET['products_context'] == 'to-sell')
        			$_GET['context'] = Search::getContextOption('sell', 'product');
        		unset($_GET['products_context']);
        	} elseif(isset($_GET['services_context'])) {
        		$_GET['services_context'] = Yii::t('inv.url', $_GET['services_context']);
        		if($_GET['services_context'] == 'requests')
        			$_GET['context'] = Search::getContextOption('buy', 'service');
        		elseif($_GET['services_context'] == 'offers')
        			$_GET['context'] = Search::getContextOption('sell', 'service');
        		unset($_GET['services_context']);
            }
        						 
        }
        
//         var_dump($route);	// debug
//         var_dump($_GET);	// debug
        
        return $route;
    }
    
    public function parseUrl_20190908($request)
    {
//     	echo '<br>UrlManager->parseUrl()';
        // sprawdzamy, czy url nie jest subdomena
        if(isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } else {
            $host = $_SERVER['SERVER_NAME'];
        }
        $baseUrl = $request->getBaseUrl();
        $hostInfo = $request->getHostInfo();
        echo '<br>host: '.$host;
//        echo '<br>$baseUrl: '.$baseUrl;
        echo '<br>hostInfo: '.$hostInfo;
//        echo '<br>getRequestUri: '.$request->getRequestUri();
//        echo '<br>getIsSecureConnection: '.$request->getIsSecureConnection();
//        echo '<br>';
//        var_dump($request);
//        exit;
        if ($hostInfo != 'https://'.$host) {
            if (substr($hostInfo, 7, 4) == 'www.'
                    && 'https://www.'.$host == $hostInfo) {
                // prefix with www.
//                echo '<br>host: '.$host;
                echo '<br>Aktualizacja serwisu. Opcja 1';
                exit;
                Yii::app()->request->redirect(
                        'https://www.'.$host.
                        $request->getRequestUri(),
//                        $request->getRequestUri().'&t=3',
                        301);
                exit();
            } /*else if (substr($hostInfo, -(strlen($host))) != $host) {
				// not primary domain
                Yii::app()->request->redirect(
                        $hostInfo.
                        $request->getRequestUri(),
                        301);
				exit();
            }*/ else {
                echo '<br>Aktualizacja serwisu. Opcja 2';
                $domain = substr($hostInfo, 7);
                $subdomain = substr($host, 0, -strlen($domain)-1);
                $route = $this->parseSubdomain($subdomain);
            }
            exit;
        } else {
            // parsujemy url
            $route = parent::parseUrl($request);
        }

        $creatorsMode = Yii::app()->params['websiteMode'] == 'creators';

        // sprawdzamy, czy parametr języka na pewno został wycięty ze ścieżki (chyba coś jest nie tak z regułami url)
        /*if (Func::startsWith($route, $_GET['language'].'/')) {
            $len = strlen($_GET['language']);
            if (count($route) == $len or isset($route[$len]) and $route[$len] == '/') {
                $route = substr($route, $len + 1);
            }
        }*/

        // po sparsowaniu powinien się pojawić parametr _GET['language']
        // (o ile url go posiadał)
        // oraz pozostałe parametry, które należy przetłumaczyć.
        $language = $this->retrieveLanguage();

        // sprawdzanie aliasow
        if (!empty($route) && $route[0] == '_') {
            // Zwykły url lub akcja globalnego kontrolera z poziomu Creators.
            $route = substr($route, 1);
            $this->globalRouteMode = true;

            if ($creatorsMode) {
                // Zabezpieczenie przed niedozwolonymi adresami
                $uri = $request->getRequestUri();
                //$uri = trim($uri, '/');	// Chyba nie
                if (strpos($uri, '?') !== false) $uri = substr($uri, 0, strpos($uri, '?'));
                $toHash = (Yii::app()->params['key']['systemSalt']) . ':' . $uri;//$route;
                //var_dump($toHash);
                if ($_GET['creatorsHash'] != substr(md5($toHash), 0, 10)) {
                        throw new CHttpException(403, 'Incorrect hash.');
                }
                /*$found = false;
                foreach($this->globalRouteModeAllowetRoutes as $allowed) {
                        if (Func::startsWith($route, $allowed)) {
                                $found = true;
                                break;
                        }
                }
                if (!$found) {
                        //throw new CHttpException(404, 'Incorrect URL address.');
                }*/
            } else {
                // Sprawdzamy czy route zaczyna się od _c, co oznacza obejście pokrywania się takich tras 
                // jak _companies/contex-action z _companies/add co odpala kontrole Site lub Categories a nie Companies
                if (!empty($route) && strlen($route) > 1 && $route[0] == 'c' && $route[1] == '/') {
                        $route = substr($route, 2);
                }  
            }
        } else {
            if ($creatorsMode) {
                // Jest to wewnętrzny link Creators
                if (substr($route, 0, 9) != 'creators/') {
                            // creators route
                    $route = 'creators/'.$route;
                }
            } else {
                // Alias product/service/company
                if (!empty($route)) {
                    if (strlen($route) > 3 && $route[2] == '/') {
                        // przy aliasie reguły url nie zadziałają
                        // i trzeba wyciąć język ręcznie
                        $language = substr($route, 0, 2);
                        $_GET['language'] = $language;
                        $this->retrieveLanguage();

                        $route = substr($route, 3);
                        $prefix = $language.'/';
                    } else {
                        $prefix = '';
                    }

                    $itemType = Yii::app()->db->createCommand()
                            ->select('cache_type')
                            ->from('tbl_item')
                            ->where('alias=:alias', array(':alias'=>$route))
                            ->queryScalar();
                    if (empty($itemType)) {
                        throw new CHttpException(404, Yii::t('item', 'Object does not exist.'));
                    }
                    // alias do firmy, produktu, lub usługi
                    $controllers = array(
                        'p' => 'products',
                        's' => 'services',
                        'c' => 'companies',
                    );

                    return $controllers[$itemType].'/show/name/'.$route;
                }
            }
        }


        /*if (Yii::app()->request->isAjaxRequest) {
            return $route;
        }*/

        /*if (Yii::app()->request->getParam('ajax')) {
            return $route;
        }*/

        // teraz mamy już na pewno ścieżkę w postaci 'cośtam/cośtam...'
        if ($language != Yii::app()->sourceLanguage) {
            /////////////////////////////
            // moduł, kontroler, akcja //
            /////////////////////////////
            $r = explode('/', $route);
            $modules = Yii::app()->getModules();
            $messagesPath = 'inv.';
            // licznik counter przelicza ile fragmentów ścieżki przetłumaczyć.
            // gdy counter==0 to parsujemy moduł lub kontroler;
            // gdy counter==1 to parsujemy akcję;
            // gdy counter==2 to doszliśmy do parametrów get, więc pomijamy tłumaczenie cyfr
            // tutaj cache, aby nie wczytywać wszystkich modułów w poszukiwaniu tłumaczenia
            $modulesTranslationsInv = (Yii::app()->cache->get('UrlManager.modulesTranslationsInv'));
            if ($modulesTranslationsInv === false) {

                $modulesTranslations = array();
                foreach ($modules as $moduleName => $module) {
                    if ($moduleName != 'gii' and $moduleName != 'rights') {
                        // importowanie!
                        Yii::import('application.modules.' . $module['class'], true);
                        $modulesTranslations[$moduleName] = Yii::t($moduleName . 'Module.url', $moduleName);
                    }
                }
                $modulesTranslationsInv = array_flip($modulesTranslations);
                //$dependency = new CDirectoryCacheDependency(Yii::app()->basePath.'/modules');	// te zależności raczej się nie przydadzą
                //$dependency->recursiveLevel = 1;
                Yii::app()->cache->set('UrlManager.modulesTranslationsInv', $modulesTranslationsInv, 0 /* , $dependency */);
            }

            $counter = 0;
            $messageSource = Yii::app()->messages;
            $language = Yii::app()->language;
            foreach ($r as $i => $routePart) {
                // tłumaczenie...
                if ($routePart != '') {
                    if ($counter < 2) { // czy moduł, kontroler lub akcja
                        if (!isset($this->paramsTranslationIgnore[$routePart])) {
                            if ($counter == 0 and isset($modulesTranslationsInv[$routePart])) { // jeżeli istnieje taki moduł, to tłumaczenie pochodzi z modułu
                                $r[$i] = $modulesTranslationsInv[$routePart];
                                $messagesPath .= $r[$i] . 'Module.';
                                // wymuszamy wcześniejsze importowanie klasy modułu,
                                // żeby można było pobrać z niej tłumaczenie
                                Yii::import('application.modules.' . ($modules[$r[$i]]['class']), true);
                                $counter = -1; // zostanie zinkrementowane na 0
                            } else { // to już jest kontroler albo akcja
                                $r[$i] = Yii::t($messagesPath . 'url', $routePart);
                            }
                        }
                    } else if ($counter % 2 == 0) { // klucz parametru
                        if ($counter == 2) { // pierwszy klucz
                            // uzupełniamy messagesPath o url i zaznaczamy, że już to zrobiono
                            $messagesPathWithUrl = $messagesPath . 'url';

                            // potrzebujemy parametr ajax, aby określić czy w ogóle tłumaczyć parametry
                            // czy znajduje się on w get?
                            if (isset($_GET['ajax'])) {
                                $ignoreAllParamsTranslation = true;
                                //break;	// nie przerywamy tłumaczenia dla path, a jedynie dla get
                            }
                            // a może znajduje się w route...
                            $ajaxPos = array_search('ajax', $r);
                            if ($ajaxPos != false) { // znaleziono ajax
                                // tutaj sprawdzamy czy słowo 'ajax' jest kluczem parametru
                                if (($ajaxPos - $i) % 2 == 0) {
                                    $ignoreAllParamsTranslation = true;
                                    //break;	// nie przerywamy tłumaczenia dla path, a jedynie dla get
                                }
                            }
                        }

                        $translateCurrent = false;
                        $lastKey = $routePart;
                        if (isset($this->paramsTranslationIgnore[$routePart]) == false) {
                            // parametry z paramsTranslationIgnore są pominięte
                            $r[$i] = $lastKey = $this->translateKey($routePart, $messagesPathWithUrl, null, true);
                        }
                    } else { // wartość parametru
                        if ($messageSource->translateCategoryExists($messagesPath . $lastKey, $language)) {
                            $r[$i] = Yii::t($messagesPath . $lastKey, $routePart);
                        }
                    }
                }
                $counter++;
            }
            $route = implode('/', $r);

			// Creators - allowed global routes
			//var_dump($route);
			//isset($this->creatorsAllowedGlobalRoutes[$route])*/

            //var_dump($route);	// debug
            //var_dump($_GET);	// debug
            ///////////////////
            // parametry GET //
            ///////////////////
            // nie tłumaczymy niczego, jeżeli wykryto parametr ajax (wyżej)
            if (!isset($ignoreAllParamsTranslation)) {
                $messagesPathWithUrl = $messagesPath . 'url';
                $language = Yii::app()->language;
                foreach ($_GET as $key => $value) {
                    if (isset($this->paramsTranslationIgnore[$key]) == false) {
                        // parametry z paramsTranslationIgnore są pominięte
                        unset($_GET[$key]);
                        $newKey = $this->translateKey($key, $messagesPathWithUrl, null, true);
                        if (!is_array($value) && $messageSource->translateCategoryExists($messagesPath . $newKey, $language)) {
                            $_GET[$newKey] = Yii::t($messagesPath . $newKey, $value);
                        } else {
                            $_GET[$newKey] = $value;
                        }
                    }
                }
            }
            unset($_GET['language']);
        }
        
        if($route == 'categories/show' || $route == 'site/index') {
        	if(isset($_GET['companies_context'])) {
        		$_GET['companies_context'] = Yii::t('inv.url', $_GET['companies_context']);
        		if($_GET['companies_context'] == 'buying')
        			$_GET['context'] = Search::getContextOption('buy', 'company');
        		elseif($_GET['companies_context'] == 'selling')
        			$_GET['context'] = Search::getContextOption('sell', 'company');
        		unset($_GET['companies_context']);
        	} elseif(isset($_GET['products_context'])) {
        		$_GET['products_context'] = Yii::t('inv.url', $_GET['products_context']);
        		if($_GET['products_context'] == 'to-buy')
        			$_GET['context'] = Search::getContextOption('buy', 'product');
        		elseif($_GET['products_context'] == 'to-sell')
        			$_GET['context'] = Search::getContextOption('sell', 'product');
        		unset($_GET['products_context']);
        	} elseif(isset($_GET['services_context'])) {
        		$_GET['services_context'] = Yii::t('inv.url', $_GET['services_context']);
        		if($_GET['services_context'] == 'requests')
        			$_GET['context'] = Search::getContextOption('buy', 'service');
        		elseif($_GET['services_context'] == 'offers')
        			$_GET['context'] = Search::getContextOption('sell', 'service');
        		unset($_GET['services_context']);
            }
        						 
        }
        
//         var_dump($route);	// debug
//         var_dump($_GET);	// debug
        
        return $route;
    }
}
