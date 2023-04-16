<?php
/**
 * Klasa kontrolera.
 * 
 * Dodaje m.in. zabezpieczenie rights, breadcrumbs, ajax in inne.
 * 
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Controller extends RController 
{
    /**
     * Nazwa akcji (jeśli nie podana to generowana i tłumaczona automatycznie).
     * @var string|null
     */
    public $name = null;
    /**
     * Nazwa akcji (jeśli nie podana to generowana i tłumaczona automatycznie).
     * @var string|null
     */
    public $actionName = null;
    /**
     * Layout domyślny dla wszystkich akcji.
     * @var string
     */
    public $layout = null;  // wymusza użycie szablonu Yii::app()->layout lub modułu
    /**
     * Linki breadcrumbs (jeśli nie podana to generowana i tłumaczona automatycznie).
     * @var array|null
     */
    public $breadcrumbs = null;
    /**
     * Kontroler nadrzędny (potrzebne do generowania breadcrumbs).
     * @var string|null
     */
    public $parentController = null;
    /**
     * Wynik zwracany przy zapytaniu ajax.
     * Możliwa zawartość tablicy:
     * script - tablica skryptów do uruchomienia.
     * alerts - tablica komunikatów array(type, message).
     * @var array
     */
    public $ajaxResult = array();
    
    public $_editorEnabled = false;
    
    /**
     * Zapamiętana nazwa strony.
     * @var string|null
     */
    protected $_myPageTitle = null;
    
    protected $ignoreRequest = false;
    
    /**
     * Czy renderowany content ma być zawarty w 'container' w szablonie.
     * (tylko Creators)
     * @var type boolean
     */
    public $noContainer = false;
    
    /**
     * Czy renderować header, stopkę itd.
     * (tylko Creators)
     * @var type boolean
     */
    public $noPartials = false;
    
    /**
     * inny favicon.
     * (tylko Creators)
     * @var type boolean
     */
    public $customFavicon = false;
    
    /**
     * Źródło tłumaczeń.
     * @see Controller::t2()
     * @var type string
     */
    protected $translationSourceDirect = null;
    
    /**
     * Alert o niedostępności usługi w pakiecie.
     * @var type boolean
     * @var type string
     */
    public $tooLowPackage = false;
    public $tooLowPackageMessage = null;

	/**
	 * Caching variable
	 **/
	public $creatorsMode = false;
	
    public $pageDescription = null;
	
    public $robotsIndex = true;
    
    public $robotsFollow = true;
    
    public $canonicalUrl = null;
	
    /**
     * Czy ladowac biblioteki jspod Gooogle reCaptcha
     * @var type bool
     */
    public $loadRecaptchaAPI = false;
    
    /**
     * Zwraca filtry i ich konfigurację.
     * @return array Filtry.
     */
    public function filters() {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    /**
     * Nazwy akcji.
     * @return array
     */
    public function actionNames() 
    {
        return array();
    }

    /**
     * Funkcja wywoływana przez uruchomieniem akcji.
     * Dodana obsługa breadcrumbs, generowania nazw akcji.
     * @param string $action Uruchamiana akcja.
     * @return boolean Czy można wykonać akcję.
     */
    public function beforeAction($action) 
    {
//        echo '<br>beforeAction';
        if (Yii::app()->params['websiteMode'] == 'creators') {
                $this->creatorsMode = true;
        }

        // make sure that we use Creators layout,
        // even if we run controller outside Creators module.
        if ($this->creatorsMode) {
                $this->layout = '//../modules/creators/views/layouts/main';
        }
		
        // creators terms of use
        $user = Yii::app()->user;
        if ($this->creatorsMode
                && $user->isGuest == false 
                && $user->getModel()->creators_tou_accepted == false) {
            // po zalogowaniu jako nie pytamy o zatwierdzenie regulaminu    	
            if (($this->id != 'site' || $action->id != 'accept_terms_of_use') && !Yii::app()->user->hasState("realUsername")) {
                $found = false;
                $allowedActions = explode(',', $this->allowedActions());
                foreach($allowedActions as $allowedAction) {
                    if (trim($allowedAction) == $action->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    Yii::app()->getRequest()->redirect($this->createUrl('/creators/site/accept_terms_of_use'));
                }
            }
        }
        
        
        // generate name of action
        $actionNames = $this->actionNames();
        if (isset($actionNames[$action->id])) {
            $this->actionName = $actionNames[$action->id];
        } else {
            $this->actionName = $this->idToName($action->id);
        }
        
        // generate name of controller
        if (empty($this->name)) {
            $this->name = $this->idToName($this->id);
        }

        // title
        /*if ($this->pageTitle === null or $action->id === 'page') {
            $this->pageTitle = Yii::app()->name . ' - ' . $this->actionName;
        }*/
        
        // breadcrumbs
        if ($this->breadcrumbs === null) {
            $this->breadcrumbs = array();
            $parentController = $this->parentController;

            while ($parentController != null) {
                $parentControllerName = $parentController . 'Controller';
                Yii::import('application.controllers.' . $parentControllerName, true);
                $parentControllerObject = new $parentControllerName($parentControllerName);
                $name = $parentControllerObject->name;
                if ($name === null) {
                    $name = $this->idToName($parentController);
                }
                $this->breadcrumbs [] = $name;
                $parentController = $parentController->parentController;
            }
            if ($this->id != Yii::app()->defaultController) {
                $this->breadcrumbs [$this->t2($this->name)] = $this->createUrl($this->id.'/'.$this->defaultAction);
            }
            if ($this->defaultAction != $action->id) {
                $this->breadcrumbs [] = $this->t2($this->actionName);
            }
        }
        
        return parent::beforeAction($action);
    }

    /**
     * Przekształca id w nazwę (m.in. zamienia podkreślenia na spacje).
     * @param string $id ID.
     * @return string Nazwa.
     */
    protected function idToName($id) 
    {
        $id = preg_replace(
                array('/_/', '/[A-Z]+/'), array(' ', ' $0'), $id
        );
        return ucfirst(trim(strtolower($id)));
    }
    
    public function beforeRender($view)
    {
        // make sure that editor is automaticaly disabled 
        // (when not available)
        if ($this->editorEnabled == false) {
            //$this->_editor = Yii::app()->session['editor'];
            Yii::app()->session['editor'] = false;
        }
        
        return parent::beforeRender($view);
    }
    
    /*public function afterRender($view)
    {
        Yii::app()->session['editor'] = $this->_editor;
    }*/
    
    public function requestLogin() 
    {
    	$this->setReturnUrl($this->action);
        
        $this->redirect($this->createUrl('/account/login'));
    }
    
    public function requestCreatorsLogin()
    {
    	$this->setReturnUrl($this->action);
    
    	$this->redirect(Yii::app()->homeUrl);
    }
    
    /**
     * Funkcja wywoływana po akcji.
     * Ustawia returnUrl.
     * @param string $action Akcja, która właśnie została zakończona.
     */
    public function afterAction($action) 
    {    
    	parent::afterAction($action);
        
        if (!$this->ignoreRequest) {
            $this->setReturnUrl($action);
        }
    }
    
    public function setReturnUrl($action) 
    {    	
        $request = Yii::app()->request;

        // redirect url after login
        if ($request->isPostRequest == false 
                and $request->isAjaxRequest == false
                and ($this->id != 'account' or $action->id != 'login')
                and ($this->id != 'account' or $action->id != 'logout')
        		and !$this->creatorsMode
//                 and (!$this->creatorsMode or $this->id != 'site' or $action->id != 'index')
                and (Yii::app()->errorHandler->error == false) ) {
            Yii::app()->user->returnUrl = $request->url;
        }
        
        // Dodane ponieważ powyższy warunek nie zezwalał na ustawienie returnUrl co powodowało przekierowanie na site/css
        if($request->isPostRequest == false 
                && $request->isAjaxRequest == false
        		&& $this->creatorsMode && $this->id == 'site' && $action->id == 'index') {
        	Yii::app()->user->returnUrl = array('companies/list');
        }

        // remember last controller and action
        if ($request->isPostRequest == false 
                and $request->isAjaxRequest == false) {
            Yii::app()->session['lastRequest'] = array(
                'controller' => $this->id,
                'action' => $action->id,
                'params' => $_GET,
                );
            // to jest chyba średni używane, wystarczy wyszukać w projekcie lastRequest
        }      

    } 
    
    /**
     * Renderuje widok.
     * Dodana obsługa ajax (pomija layout).
     * @param string $view Alias do widoku.
     * @param array $data Parametry.
     * @param boolean $return Czy przechwycić i zwrócić echo.
     * @param boolean $renderLayout Czy opakowac partiala w layout w trybie ajax
     * @return string|null Wyrenderowany widok, jeśli $return=true
     */
    public function render($view, $data=NULL, $return=false, $renderLayout=false) {    	
//        return parent::render($view, $data, $return);
        if (Yii::app()->request->isAjaxRequest && !$renderLayout) {
            return $this->renderPartial($view, $data, $return);
        } else {
            return parent::render($view, $data, $return);
        }
    }
    
    
    /**
     * Funkcja tłumaczenia.
     * Pozwala na tłumaczenie bez podawania kategorii.
     * Kategorią jest źródło w obecnym module o nazwie tej samej, co klasa.
     * @param string $message Tekst do przetłumaczenia.
     * @return string Przetłumaczony tekst.
     */
    public function t2($message)
    {
           if ($this->module !== null) {
                   if ($this->translationSourceDirect === null) {
                           $this->translationSourceDirect = $this->module->id.'Module.'.$this->id;
                   }
                   return Yii::t($this->translationSourceDirect, $message);
           } else return Yii::t($this->id, $message);
    }
    
    /**
     * Setter dla tytułu strony.
     * @param string $pageTitle Nowy tytuł.
     */
    public function setPageTitle($pageTitle) {
        $this->_myPageTitle = $pageTitle;
    }
    
    /**
     * Getter dla tytułu strony.
     * Generuje tytuł, jeśli nie jest ustawiony.
     * @return string Tytuł strony.
     */
    public function getPageTitle() {
        if($this->_myPageTitle!==null)
            return $this->_myPageTitle;
        else
        {
            $this->_myPageTitle = Yii::app()->name;
            if ($this->id === Yii::app()->defaultController) {
                if ($this->action->id !== $this->defaultAction) {
                    $this->_myPageTitle .= ' - '.$this->t2(ucfirst($this->actionName));
                }
            } else {
                if ($this->action->id !== $this->defaultAction) {
                    $this->_myPageTitle .= ' - '.$this->t2(ucfirst($this->id)).' - '.$this->t2(ucfirst($this->actionName));
                } else {
                    $this->_myPageTitle .= ' - '.$this->t2(ucfirst($this->id));
                }
            }
        }
        return $this->_myPageTitle;
    }
    
    /*public function redirect($url) {
        if (Yii::app()->request->isAjaxRequest) {
            
            // location
            $this->result['redirect'] = $url;
            
            $this->endCoolAjax();
        } else {
            parent::redirect($url);
        }
    }*/
    
    
    /**
     * Dodaje do $ajaxResult skrypt.
     * @param string $script Skrypt Javascript.
     */
    public function runScript($script) {
        if (!isset($this->ajaxResult['script'])) {
            $this->ajaxResult['script'] = array();
        }
        
        $this->ajaxResult['script'] []= array('script'=>$script);
    }
    
    
    /**
     * Dodaje do $ajaxResult skrypt odświeżający listę ListView i 
     * przewijający na pierwszą stronę.
     * @param string $listId ID listy ListView.
     */
    public function listViewFirstPage($listId) {
        $this->runScript('jQuery.fn.yiiListView.update(\''.$listId.'\', 
            {url: jQuery.fn.yiiListView.getUrl(\''.$listId.'\')+\'?ajax='.$listId.'\'});');
    }
    
    /**
     * Dodaje do $ajaxResult skrypt odświeżający listę ListView.
     * @param string $listId ID listy ListView.
     */
    public function listViewRefresh($listId) {
        $this->runScript('jQuery.fn.yiiListView.update(\''.$listId.'\', '
                . '{url: jQuery(\'#'.$listId.' .pagination .active a\').attr(\'href\') '
                . ' || jQuery.fn.yiiListView.getUrl(\''.$listId.'\')});');
    }
    
    /**
     * Dodaje do $ajaxResult skrypt odświeżający tabelę GridView i 
     * przewijający na pierwszą stronę.
     * @param string $gridId ID listy GridView.
     */
    public function gridViewFirstPage($gridId) {
        $this->runScript('jQuery(\'#'.$gridId.'\').yiiGridView(\'update\', '    // update
                . '{url: jQuery(\'#'.$gridId.' .pagination li\').not(\'.previous\').find(\'a\').first().attr(\'href\') ' // url from menu
                . ' || jQuery(\'#'.$gridId.'\').yiiGridView(\'getUrl\')});'); // or eventually current in grid
    }
    
    /**
     * Dodaje do $ajaxResult skrypt odświeżający tabelę.
     * @param string $gridId ID tabeli GridView.
     */
    public function gridViewRefresh($gridId) {
        $this->runScript('jQuery(\'#'.$gridId.'\').yiiGridView(\'update\');');
    }
    
    
    /**
     * Kończy akcję i odpowiada na zapytanie ajax (zwraca JSON).
     */
    public function endCoolAjax() {
        // alerts
        $flashes = Yii::app()->user->getFlashes();
        if ($flashes) {
            $this->ajaxResult['alerts'] = array();
            foreach($flashes as $key => $message) {
                $this->ajaxResult['alerts'] []= array(
                    'type' => $key,
                    'message' => $message
                );
            }
        }

        echo CJSON::encode($this->ajaxResult);
        Yii::app()->end();
    }
    
    /**
     * Wyłącza logi na stronie.
     * Potrzebne w przypadku gdy zwracamy JSON.
     */
    public function ajaxMode() 
    {
        $this->ignoreRequest = true;
                
        foreach (Yii::app()->log->routes as $route) {
            if (get_class($route) == 'CWebLogRoute'
                    or is_subclass_of($route, 'CWebLogRoute')) {
                $route->enabled=false;
            }
        }
    }
    
    
    public function getEditorEnabled()
    {
        return $this->_editorEnabled;
    }
    
    public function setEditorEnabled($editorEnabled)
    {
       $this->_editorEnabled = $editorEnabled;
       if ($editorEnabled == false) {
           Yii::app()->session['editor'] = false;
       }
    }
    
    public function createFirmbookUrl($route,$params=array())
    {
        return Yii::app()->getUrlManager()->createUrl(trim($route,'/'),$params,'&','firmbook');
    }

    public function createCreatorsUrl($route,$params=array())
    {
    	return Yii::app()->getUrlManager()->createUrl(trim($route,'/'),$params,'&','creators');
    }
	
	/**
	 * Tworzy link do akcji globalnego kontrolera (nie z modułu),
	 * który działa na obu serwisach.
	 * W przypadku Firmbook tworzy zwykły link.
	 * W przypadku Creators dodaje prefix _ i cratorsHash,
	 * rozpoznawane w UrlManager::parseUrl.
	 * Hash zapewnia, że tylko wybrane akcje są dostępne z poziomy Creators.
	 **/
	public function createGlobalRouteUrl($route,$params=array())
    {
		// From CContorller
		if($route==='')
			$route=$this->getId().'/'.$this->getAction()->getId();
		elseif(strpos($route,'/')===false)
			$route=$this->getId().'/'.$route;
		/*if($route[0]!=='/' && ($module=$this->getModule())!==null)
			$route=$module->getId().'/'.$route;*/
		if ($route[0] === '/' && strlen($route) > 1) {
			$route = substr($route, 1);
		}

  		return Yii::app()->getUrlManager()->createUrl($route, $params, '&', 'all');
    }
	
	public function processOutput($output)
    {
        if (is_a(Yii::app(), 'WebApplication')) {
            return parent::processOutput($output);
        } else {
            // no ClientScript, no caching
            return $output;
        }
    }
    
    public function setRobotsIndex($index)
    {
    	$this->robotsIndex = $index ? true : false;
    }
    
    public function getRobotsIndex()
    {
    	return $this->robotsIndex;
    }
    
    public function setRobotsFollow($follow)
    {
    	$this->robotsFollow = $folllow ? true : false;
    }
    
    public function getRobotsFollow()
    {
    	return $this->robotsFollow;
    }
    
    public function setCanonicalUrl($canonicalUrl)
    {
    	$this->canonicalUrl = $canonicalUrl;
    }
    
    public function getCanonicalUrl()
    {
    	return $this->canonicalUrl;
    }
    
    public function setLoadRecaptchaAPI($value)
    {
        $this->loadRecaptchaAPI = $value;
    }      
    
    public function getLoadRecaptchaAPI()
    {
        return $this->loadRecaptchaAPI;
    }
}
