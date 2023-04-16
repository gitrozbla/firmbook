<?php
/**
 * Klasa przechowująca pewne dane użytkownika.
 *
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class WebUser extends RWebUser
{

    /**
     * Przechowuje model zalogowanego użytkownika
     * @var User
     */
    protected $_user = null;
    
    public function init()
    {    	
        parent::init();
        
    	//logowanie poprzez cookie
    	/*if($this->isGuest) {
    		//zakomentowane z powodu błędnego działania dla package_id, którego nie można zmienic po ladowaniu z cookie 
    		//$this->restoreFromCookie();
    		
    		$this->renewCookie();
    		$this->saveToCookie(Yii::app()->params['rememberTime']);
    	}*/	
    	//print_r($this->identityCookie);
    	
        if (Yii::app()->session['editor'] === null) {
            // do not allow null
            // needed to work Editable widgets propertly
            Yii::app()->session['editor'] = false;
        }
        //dodane w celu obłsugi plików cookie dla subdomen
        /*$conf = Yii::app()->session->cookieParams;
        $this->identityCookie = array(
            'path' => $conf['path'],
            'domain' => $conf['domain'],
        );*/
        //
    }
    
   /**
     * Sprawdza czy użytkownik jest administratorem.
     * @TODO Cache wartości.
     * @return boolean
     */
    public function getIsAdmin() {
        if ($this->isGuest == false) { 
            $roles = Rights::getAssignedRoles($this->id);
            return (isset($roles['Superadmin']));
        }
        return false;
    }


    /*protected $_accessWithParams = array();

    protected $_accessWithParamsNoSession = array();

    protected $getInCheckAccess = true;*/

    /**
     * Pobiera model zalogowanego użytkownika
     */	
    public function getModel() 
    {
            if ($this->_user == null) {
                    $this->_user = User::model()->findByAttributes(array('id'=>$this->id));
            }
            return $this->_user;
    }


    /*public function getModelWith($with=null) 
    {
            if ($this->_user == null) {
                    if ($with != null) {
                            $this->_user = User::model()->with($with)->findByPk((int)$this->id);
                    } else {
                            $this->_user = User::model()->findByPk((int)$this->id);
                    }
            }
            return $this->_user;
    }*/


    /**
     * Ustawia returnUrl na obecny.
     * 
     * @see CWebUser::$returnUrl
     * Funkcja odcina domenę.
     * Możliwość niepodawania parametru - wtedy ustawiany url automatycznie na obecny.	 
     *
     * @param string $value Url, jaki ma zostać ustawiony.
     */
    public function setReturnUrl($value = null) 
    {
            if ($value != null) return parent::setReturnUrl($value);

            $referrer = Yii::app()->request->urlReferrer;
            if ($referrer == '') return parent::setReturnUrl('/');

            // czy domena i folder główny taki sam?
            //$parts = parse_url($referrer);
            //$begin = $parts['scheme'].'://'.$parts['host'];
            $domainAndBase = Yii::app()->request->hostInfo.Yii::app()->request->baseUrl;
            $length = strlen($domainAndBase);
            if (substr($referrer, 0, $length)==$domainAndBase) {

                    // jeśli poprzedni url był taki sam to przenosimy na index
                    if (Yii::app()->request->hostInfo.Yii::app()->request->requestUri == $referrer) 
                            return parent::setReturnUrl('/');			

                    // ta sama domena i folter główny - odcinamy tylko dalszą część
                    $referrer = substr($referrer, $length);
            } else {
                    return parent::setReturnUrl('/');
            }

            $this->setReturnUrl($referrer);

    }

    /*public function beforeLogin($id, $states, $fromCookie)//$id, $states, $fromCookie
    {
    	echo 'logowanie';
    	//parent::beforeLogin();
    	return true;
    }*/
    
    /**
     * Logowanie użytkownika.
     * Ustawia jego język.
     * @TODO Przeanalizować, poprawić wybór języka.
     * @param UserIdentity $identity Obiekt identyfikujący użytkownika.
     * @param int $duration Czas zalgoowania.
     * @return boolean Czy zalogowano pomyślnie.
     */
    public function login($identity, $duration=0)
    {
        //Yii::app()->session['touAccepted'] = $identity->getTouAccepted();

        $result = parent::login($identity, $duration);

        // language
        $user = $this->getModel();
        //$this->setState('language', 'en');//$user['language']);
        // save language in cookie even if remember_me is disabled
        //Yii::app()->request->cookies['language'] = new CHttpCookie('cookie_name', 'en');

        return $result;
    }

    /*public function isTouAccepted()
    {
            if ($this->isGuest) return true;
            else return Yii::app()->session['touAccepted'];
    }*/

    /*public function touAccept()
    {
            $user = $this->getModel();
            $user->tou_accepted = 1;
            if ($user->update()) {
                    Yii::app()->session['touAccepted'] = true;
                    return true;
            } else {
                    return false;
            }

    }*/

    /*public function disableGetInCheckAccess()
    {
            $this->getInCheckAccess = false;
    }*/


    // + automatyczne ustawiania pearmetrow na odstawie get
    // Tutaj zmieniona jest koncepcja cache'owania.
    // Parametr $allowCaching ustawiony na false oznacza, że cache nie jest zapamiętywany w sesji.
    /*public function checkAccess($operation, $params=array(), $allowCaching=true)
    {
            // debug
            //var_dump(func_get_args());

            // sortowanie - jest potrzebne dla porównywania parametrów
            asort($params);	
            if ($this->getInCheckAccess) {
                    $params += $_GET;
            }

            // szukanie w _accessWithParams
            if (isset($this->_accessWithParams[$operation])) 
            {
                    foreach($this->_accessWithParams[$operation] as $option) {
                            if ($option['params'] === $params) {
                                    // parametry się zgadzają
                                    $result = $option['result'];
                                    break;
                            }
                    }
            }
            // szukanie w _accessWithParamsNoSession
            if (!isset($result) and isset($this->_accessWithParamsNoSession[$operation])) 
            {
                    foreach($this->_accessWithParamsNoSession[$operation] as $option) {
                            if ($option['params'] === $params) {
                                    // parametry się zgadzają
                                    $result = $option['result'];
                                    break;
                            }
                    }
            }

            // jeżeli nie znaleziono
            if (!isset($result)) {
                    // parent, bez cache
                    $result = parent::checkAccess($operation, $params, false);

                    // debug
                    if (defined('YII_DEBUG')) {
                            Yii::log('Access to item: '.$operation.'; params: '.var_export($params, true).'; result: '.var_export($result, true), 'info', 'WebUser');
                    }

                    // zapamiętywanie
                    if ($allowCaching) {
                            $this->_accessWithParams[$operation][] = array('params'=>$params, 'result'=>$result);
                    } else {
                            $this->_accessWithParamsNoSession[$operation][] = array('params'=>$params, 'result'=>$result);
                    }
            }

        return $result;
    }*/


	
    /*public function loadRightsCache() 
    {
            if (!defined('YII_DEBUG')) {
                    $this->_accessWithParams = unserialize(Yii::app()->session['WebUserRightsTable']);	// !!!
                    if ($this->_accessWithParams == false) $this->_accessWithParams = array();
            }
    }

    public function saveRightsCache() 
    {
            if (!defined('YII_DEBUG')) {
                    Yii::app()->session['WebUserRightsTable'] = serialize($this->_accessWithParams);
            }
    }*/
	
}
