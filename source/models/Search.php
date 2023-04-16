<?php
/**
 * Model forumlarza wyszukiwania.
 * 
 * @category models
 * @package search
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Search extends ActiveRecord
{
    /**
     * alias kategorii
     * @var type string
     */
    public $category = null;
    
    /**
     * Typ (product/service/company)
     * @var type string
     */
    public $type = 'company';
    //public $type = 'product';
    /**
     * Akcja (buy/sell)
     * @var type string
     */
    public $action = 'sell';
    
    public $username = null;
    
    // dodane poniewaz wystapil problem na nowym odowisku, uniemozliwiajacy dodanie ostatniego wyszukania bez
    // bez wartosci w polu search
    public $search = '';
    
    protected $_isAdvanced = null;
    
    /**
     * Kontekst, na podstawie $type oraz $action.
     * Definiuje kontekst pojawiający się w url
     * @var type array
     */
    protected static $contextOptions = array(
        'buy-product'=>'products-to-buy',
        'sell-product'=>'products-to-sell',
        'buy-service'=>'services-requests',
        'sell-service'=>'services-offers',
        'buy-company'=>'buying-companies',
        'sell-company'=>'selling-companies',
    );
    
    /**
     * Kontekst, na podstawie $type oraz $action.
     * Definiuje kontekst pojawiający się jako label 
     * na przyciskach zmiany kontekstu.
     * @var type string
     */
    protected static $contextLabels = array(
        'buy-product'=>'Buy offers',
        'sell-product'=>'Sell offers',
        'buy-service'=>'Requests',
        'sell-service'=>'Offers',
        'buy-company'=>'Buying',
        'sell-company'=>'Selling',
    );
	
    protected static $searchLabels = array(
        'buy-product'=>'Search in product buy offers',
        'sell-product'=>'Search in offered products',
        'buy-service'=> 'Search in service order offers',
        'sell-service'=>'Search in offered services',
        'buy-company'=>'Search in buying companies',
        'sell-company'=>'Search in selling companies',
    );
    
    protected static $contextLongLabels = array(
    	'buy-product'=>'Buy offers',
    	'sell-product'=>'Sell offers',
    	'buy-service'=>'Services requests',
    	'sell-service'=>'Offered services',
    	'buy-company'=>'Buying companies',
    	'sell-company'=>'Selling companies',
    );
	
	/**
	 * Nazwa kotrolera  na podstawie $type.
	 * $var type string
	 */
	 protected static $typeControllers = array(
		 'company' => 'companies',
		 'service' => 'services',
		 'product' => 'products'
	 );
    
    /**
     * Rodzaj wyszukiwania (null/text/advanced)
     * @var type string
     */
    //public $search = null;
    
    /**
     * Cache obiektu, którego atrybuty zapisane są w sesji.
     * @var type Search
     */
    protected static $_sessionSearch = null;
    
    protected $advancedParams = array(
        'username',
    );
    
    /**
     * Liczba pamięci ostatnich wyszukiwań (pasek pod wyszukiwarką).
     * @var type int
     */
    static $lastSearchesCount = 5;
    
    
    /**
     * Tworzy instancję.
     * @param string $className Klasa instancji.
     * @return object Utworzona instancja zadanej klasy.
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Nazwa tabeli.
     * @return string
     */
    public function tableName()
    {
        return '{{search}}';
    }
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'id';    // because db has set clustered index
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
            'query' => Yii::t('search', 'Text'),
            'username' => Yii::t('search', 'User'),
        );
    }
    
    /**
     * Generuje kontekst dla url na podstawie $type i $action.
     * @return type string
     */
    public function getContext()
    {
        return self::$contextOptions[$this->action.'-'.$this->type];
    }
    
    /**
     * Ustawia $type i $action na podstawie zadanego kontekstu z url.
     * @param string $context Zadany kontekst.
     */
    public function setContext($context)
    {
        if ($context) {
            $flippedContextOptions = array_flip(self::$contextOptions);
            $option = $flippedContextOptions[$context];
            $parts = explode('-', $option);
            $this->action = $parts[0];
            $this->type = $parts[1];
        }
    }    
    
    /**
     * Zapisuje obiekt do sesji (serializacja).
     */
    public function saveToSession() {
        Yii::app()->session['search'] = $this->getParams();
        
        // invalidate cache
        self::$_sessionSearch = null;
    }
    
    /**
     * Pobiera obiekt z sesji (deserializacja).
     * @return Search Pobrany obiekt
     */
    public function getFromSession()
    {
        if (self::$_sessionSearch == null) {
            self::$_sessionSearch = new Search('insert');
            $params = Yii::app()->session['search'];
            if (is_array($params)) {
                self::$_sessionSearch->assignParams($params);
            }
        }
        return self::$_sessionSearch;
        
    }
    
    /**
     * Ustawia atrybuty.
     * @param Array $params Parametry.
     */
    public function assignParams($params)
    {
        foreach($params as $key=>$value) {
            //if ($value !== null) {
                if ($key=='context') {
                    $this->setContext($value);
                } else {
                    $this->$key = $value;
                }
            //}
        }
    }
    
    /**
     * Pobiera kontekst dla url na podstawie $type i $action.
     * @param string $action Akcja.
     * @param string $type Typ.
     * @return string Kontekst.
     */
    public static function getContextOption($action, $type) 
    {
        return self::$contextOptions[$action.'-'.$type];
    }
    
    /**
     * Pobiera kontekst dla nazw przycisków na podstawie $type i $action.
     * @param string $action Akcja.
     * @param string $type Typ.
     * @return string Kontekst.
     */
    public static function getContextLabel($action, $type) 
    {
        return self::$contextLabels[$action.'-'.$type];
    }
	
	public static function getSearchLabel($action, $type)
    {
        return self::$searchLabels[$action.'-'.$type];
    }

    public static function getContextLongLabel($action, $type)
    {
    	return self::$contextLongLabels[$action.'-'.$type];
    }
    
	/**
     * Pobiera nazę kontrolera dla $type.
     * @param string $type Typ.
     * @return string Nazwa kontrolera.
     */
    public static function getTypeController($type)
    {
        return self::$typeControllers[$type];
    }
    
    /**
     * Tworzy url, uwzględniając parametry wyszukiwania.
     * @param string $path Kontroler/akcja.
     * @param Array $additionalParams dodatkowe parametry, nadpisujące istniejące.
     * @return string Wygenerowany url.
     */
    
//    public function createUrl($path=null, $additionalParams=array(), $withoutCategory=false)
    public function createUrl($path=null, $additionalParams=array(), $withoutCategory=false, $absoluteUrl=false)        
    {
    	if($withoutCategory)
            $path = '/site/index';
    	elseif ($path === null) {
            $controller = Yii::app()->controller->id;
            $action = Yii::app()->controller->action->id;
//     		if ($controller != 'site' || $action != 'index') {
//     			$controller = 'categories';
//     			$action = 'show';
//     		}
            if ($controller != 'categories' || $action != 'show') {
                $controller = 'site';
                $action = 'index';
            }
            $path = '/'.$controller.'/'.$action;	
    	}
   	
    	// skoro jest parametr wylaczajacy uwzglednianie kateogrii, to poniższy kawałek można równie uzależnić od tego
    	if (!$withoutCategory && $path == '/categories/show' && !empty($this->category)) {
            $categoryName = array(
                'name' => Yii::t('category.alias', $this->category,
                array(), 'dbMessages'));
    	} else {
            $categoryName = array();
    	}

        if (isset($additionalParams['language'])) {
            $lang = $additionalParams['language']; 
        } else
            $lang = Yii::app()->language; //Yii::app()->params['defaultLanguage'];
		
    	if(!array_key_exists('companies_context', $additionalParams) 
            && !array_key_exists('products_context', $additionalParams)
            && !array_key_exists('services_context', $additionalParams)) {
            $additionalParams[self::getContextUrlType($this->type).'_context'] = Yii::t('url', self::getContextUrlAction($this->action,$this->type));
    	}
    	
    	if(array_key_exists('companies_context', $additionalParams) && $additionalParams['companies_context'] == 'selling' && $path == '/site/index') {
            if(isset($additionalParams['language']))
            {    
                if($absoluteUrl)
                    return Yii::app()->controller->createAbsoluteUrl('/', array('language'=>$additionalParams['language'])).'/';
                else
                    return Yii::app()->controller->createUrl('/', array('language'=>$additionalParams['language'])).'/';
            } else {
                if($absoluteUrl)
                    return Yii::app()->controller->createAbsoluteUrl('/').'/';
                else
                    return Yii::app()->controller->createUrl('/').'/';
            }    
    	}
    	
    	if(array_key_exists('companies_context', $additionalParams))
            $additionalParams['companies_context'] = Yii::t('url', $additionalParams['companies_context'], array(), null, $lang);
    	elseif(array_key_exists('products_context', $additionalParams))
            $additionalParams['products_context'] = Yii::t('url', $additionalParams['products_context'], array(), null, $lang);
    	elseif(array_key_exists('services_context', $additionalParams))
            $additionalParams['services_context'] = Yii::t('url', $additionalParams['services_context'], array(), null, $lang);
        
        if($absoluteUrl)
            $search_url =  Yii::app()->controller->createAbsoluteUrl(
                $path,
                $additionalParams + ($withoutCategory ? array() : $categoryName)    // add arrays, does not override values
    //     			$additionalParams + $this->getParams(true) + $categoryName    // add arrays, does not override values
            );
        else
            $search_url =  Yii::app()->controller->createUrl(
                $path,
                $additionalParams + ($withoutCategory ? array() : $categoryName)    // add arrays, does not override values
    //     			$additionalParams + $this->getParams(true) + $categoryName    // add arrays, does not override values
            );
    	return $search_url;
    }
    
    public function createUrl_org($path=null, $additionalParams=array())
    {    	
    	if ($path === null) {
    		/*$lastRequest = Yii::app()->session['lastRequest'];
    		$controller = $lastRequest['controller'];
    		$action = $lastRequest['action'];*/
    		$controller = Yii::app()->controller->id;
    		$action = Yii::app()->controller->action->id;
    
    		if ($controller != 'site' || $action != 'index') {
    				$controller = 'categories';
    		$action = 'show';
    		}
    
    		$path = '/'.$controller.'/'.$action;
    	}
    
    	if ($path == '/categories/show' && !empty($this->category)) {
	    	$categoryName = array(
		    	'name' => Yii::t('category.alias', $this->category,
		    	array(), 'dbMessages'));
    	} else {
    		$categoryName = array();
    	}

    	$test = $additionalParams + $this->getParams() + $categoryName;
       	$search_url =  Yii::app()->controller->createUrl(
        	$path,
        	$additionalParams + $this->getParams() + $categoryName    // add arrays, does not override values
       	);

       	return $search_url;
    }    
    
    /**
     * Pobiera parametry wyszukiwania
     * @return array Parametry.
     */
    protected function getParams($urlCreate=false) 
    {
        $result = array();
        
        if (!empty($this->query)) {
            $result['query'] = $this->query;
        }
        
        if (!empty($this->category)) {
            $result['category'] = $this->category;
        }
            
        foreach($this->advancedParams as $param) {
            if (!empty($this->$param)) {
                $result[$param] = $this->$param;
            }
        }
        
        if($urlCreate) {
        	$context_action_type = self::contextToActionTypeContext(self::getContextOption($this->action,$this->type));
        	$result = array_merge(
        		$result,
        		$context_action_type
        	);        			
        } else {
        	$result = array_merge(
        		$result,
        		array(                	
                    'context' => self::$contextOptions[$this->action.'-'.$this->type],
                )
        	);
        }

        return $result;
    }
    
    /**
     * Dodaje obecne wyszukiwanie do listy ostatnio wyszukiwanych
     * (przewijany pasek pod wyszukiwarką).
     * @throws CHttpException Błąd komunikacji z bazą danych.
     */
    public function addLastSearch()
    {
        if (!empty($this->query)) {
                
            $this->datetime = date('Y-m-d');
            $this->scenario = 'insert';

            $db = Yii::app()->db;
            $countCommand = $db->createCommand()
                    ->select('COUNT(*)')
                    ->from('tbl_search')
                    ->where('type=:type and action=:action', array(
                        ':type' => $this->type,
                        ':action' => $this->action
                    ));

            // transaction section
            $transaction = $db->beginTransaction();
            try
            {
                $this->deleteAll(
                        'query=:query and type=:type and action=:action',
                        array(
                            ':query' => $this->query,
                            ':type' => $this->type,
                            ':action' => $this->action
                        ));
                
                $count = $countCommand->queryScalar();
                if ($count >= self::$lastSearchesCount) {
                    $limit = $count - self::$lastSearchesCount + 1;
                    $db->createCommand()->delete(
                            'tbl_search',
                            'type=:type and action=:action order by datetime asc limit '.$limit,
                            array(
                                ':type' => $this->type,
                                ':action' => $this->action
                            )); // limit as param not supported by db
                }
                
                $this->save();   // save current search

                $transaction->commit();
            }
            catch(Exception $e)
            {
               $transaction->rollback();
               throw new CHttpException(500);
            }
            
        }
    }
    
    /**
     * Pobiera ostatnie wyszukiwania.
     * @return Array[Search] Wyszukiwania.
     */
    public function findLastSearches()
    {
        return $this->findAll(
                'type=:type and action=:action order by datetime desc',
                array(
                    ':type' => $this->type,
                    ':action' => $this->action
                ));
    }
    
    public function getIsAdvanced()
    {
        foreach($this->advancedParams as $param) {
            if (!empty($this->$param)) {
                return true;
            }
        }
        return false;
    }
    
    public static function contextToActionTypeContext($context)
    {
    	$inv_contexts = array_flip(self::$contextOptions);
    	$contex_key = $inv_contexts[$context];
    	$contex_key_parts = explode('-', $contex_key);

		switch($contex_key_parts[1]) 
		{
			case 'company':
				if($contex_key_parts[0] == 'sell')
					return array('selling', 'companies');
				else
					return array('buying', 'companies');
			case 'product':
				if($contex_key_parts[0] == 'sell')
					return array('to-sell', 'products');
				else
					return array('to-buy', 'products');
			case 'service':
				if($contex_key_parts[0] == 'sell')
					return array('offers', 'services');
				else
					return array('requests', 'services');
		} 
    }
    
    public static function getContextUrlAction($action=NULL, $type=NULL)
    {
    	if(!$action)
    		$action = $this->action;
    	switch($type)
    	{
    		case 'company':
    			if($action == 'sell')
    				return 'selling';
    			else
    				return 'buying';
    		case 'product':
    			if($action == 'sell')
    				return 'to-sell';
    			else
    				return 'to-buy';
    		case 'service':
    			if($action == 'sell')
    				return 'offers';
    			else
    				return 'requests';
    	}
    }
    
    public static function getContextUrlType($type=NULL)
    {
    	if(!$type)
    		$type = $this->type;

    	switch($type)
    	{
    		case 'company':
    			return 'companies';    			
    		case 'product':
    			return 'products';    			
    		case 'service':
    			return 'services';
    	}
    }
    
}
