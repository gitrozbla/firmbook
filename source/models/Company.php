<?php
/**
 * Model firmy.
 * 
 * @category models
 * @package company
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Company extends ActiveRecord
{
    //wykorzystane w adminSearch 
    public $item_name;
    public $item_date;
    public $user_username;
    public $package_id;
    public $package_expire;	

    //metody płatności
    //public $payment_type;

    //item columns
    //nazwa
    //public $name;
    //alias
    //public $alias;
    //opis
    //public $description;
	
    public static $legalForms = array(
        1 => 'Sole proprietorship',
        2 => 'Legal person'
	);
    
    //    public static $allegroLinkPattern = '/^.*(id=)([0-9]{1,10}).*/i';
//    public static $allegroLinkPattern = '/^https:\\/{2}allegro\\.pl[\/]uzytkownik[\/]([^\?]+)([\?]{1}.*)?$/i';
    public static $allegroLinkPattern = '/^.*allegro\\.pl[\/]uzytkownik[\/]([^\?]+)([\?]{1}.*)?$/i';
    
//    public static $youtubeLinkPattern = '/^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]{1,11}).*/i';
    public static $youtubeLinkPattern = '/^https:\\/{2}www\\.youtube\\.com[\/]user[\/]([^\?]+)([\?]{1}.*)?$/i';

        
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
        return '{{company}}';
    }
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'item_id';    // because db has set clustered index
    }
	
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(
            'item' => array(self::BELONGS_TO, 'Item', 'item_id', 'together'=>true),
            
            'products' => array(self::HAS_MANY, 'Product', 'company_id'),
            'services' => array(self::HAS_MANY, 'Service', 'company_id'),
            //'user' => array(self::BELONGS_TO, 'User', 'user_id', 'together'=>true),
            'user' => array(self::HAS_ONE, 'User', array('user_id'=>'id'), 'through'=> 'item'),
            'package' => array(self::HAS_ONE, 'Package', array('cache_package_id'=>'id'), 'through'=> 'item'),

            'origin' => array(self::BELONGS_TO, 'Country', array('country'=>'code'), 'together'=>true),
            //'origin' => array(self::HAS_ONE, 'Country', array('country'=>'code'), 'together'=>true),
            //'productCount' => array(self::STAT, 'Product', 'company_id'),
        );
    }
    
    public function rules()
    {
        $phones = 'phone, phone2, phone3, phone4, phone5';
        $emails = 'email, email2, email3, email4, email5';
        return array_merge(
            array(
                // update
                array('short_description', 'length', 'max'=>250, 'on'=>'update, create'),
                array($phones, 'phone', 'on'=>'update, create'),
                array($phones, 'length', 'max'=>20, 'on'=>'update, create'),
                array($emails, 'email', 'on'=>'update, create'),
                array($emails, 'length', 'max'=>64, 'on'=>'update, create'),
                array('skype', 'length', 'max'=>50, 'on'=>'update, create'),
                array('hide_email', 'boolean', 'on'=>'update, create'),
                array('allow_verification, allow_comment', 'boolean', 'on'=>'update, create'),
                 // search
                array('item_id, '. $emails . ', ' . $phones . ', item_name, user_username, item_date, package_id', 'safe', 'on'=>'adminSearch'), 
                array('legal_form', 'in',
                    'range' => array_merge(array_keys(Company::$legalForms), array(0)),
                    'allowEmpty' => true),
                //zabezpieczyc te pola	
                array('nip, regon, krs, city, street, province, postcode, map_lat, map_lng, street_view_active, 
                        payment_type, payment_cash, payment_wire_transfer, 
                        delivery_type, free_delivery, country', 'safe'
                ),
                array('payment_bank_account', 'length', 'min'=>32, 'max'=>45),
                array('payment_bank_account', 'match',  'pattern' => '/^[a-zA-Z0-9\-()\s]+$/'),
                array('payment_swift_code', 'length', 'min'=>8, 'max'=>11),
                array('payment_swift_code', 'match',  'pattern' => '/^[a-zA-Z0-9\s]+$/'),

                array('allegro', 'safe'),//'numerical', 'integerOnly'=>true, 'min'=>1, 'max'=>1000000000),
                array('allegro_link', 'match',
                    'pattern'=>self::$allegroLinkPattern,
                    'message'=>Yii::t('item', 'This link is incorrect or not supported. Please try using different format.')),
                array('youtube', 'length', 'min'=>1, 'max'=>100, 'on'=>'update, create'),
                array('youtube_link', 'match',
                    'pattern'=>self::$youtubeLinkPattern,
                    'message'=>Yii::t('item', 'This link is incorrect or not supported. Please try using different format.')),
                array('business_reliable', 'boolean', 'on'=>'update, create'),
            ),
            array()
            //Item::model()->rules()
        );
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array_merge(
            array(
            'item_name' => Yii::t('company', 'Name'),
//            'item_alias' => Yii::t('item', 'Alias'),
            'item_alias' => Yii::t('item', 'Alias - the most important positioning phrase'),                    
            'phone' => Yii::t('company', 'Phone'),
            'phone2' => Yii::t('company', 'Phone'),
            'phone3' => Yii::t('company', 'Phone'),
            'phone4' => Yii::t('company', 'Phone'),
            'phone5' => Yii::t('company', 'Phone'),
            
            'email' => Yii::t('company', 'Email'),
            'email2' => Yii::t('company', 'Email'),
            'email3' => Yii::t('company', 'Email'),
            'email4' => Yii::t('company', 'Email'),
            'email5' => Yii::t('company', 'Email'),
            
            'short_description' => Yii::t('company', 'Short description'),
            //'description' => Yii::t('item', 'Description'),
        	
            'legal_form' => Yii::t('company', 'Legal form'),
            'nip' => Yii::t('company', 'NIP'),
            'regon' => Yii::t('company', 'REGON'),
            'krs' => Yii::t('company', 'KRS number'),
            'country' => Yii::t('company', 'Country'),    
            'city' => Yii::t('company', 'City'),
            'street' => Yii::t('company', 'Street'),
            'province' => Yii::t('company', 'Province'),
            'postcode' => Yii::t('company', 'Postcode'),
            'street_view_active' => Yii::t('company', 'Street view'),
            'hide_email' => Yii::t('company', 'Hide email'),
            'allow_verification' => Yii::t('company', 'Enable verification'),
            'allow_comment' => Yii::t('company', 'Allow comment'),
            'payment_type' => Yii::t('company', 'Preferred payment methods'),
            'payment_cash' => Yii::t('company', 'Pay with cash'),
            'payment_wire_transfer' => Yii::t('company', 'Wire transfer'),
            'payment_bank_account' => Yii::t('company', 'Bank account number'),
            'payment_swift_code' => Yii::t('company', 'SWIFT/BIC code'),
            'delivery_type' => Yii::t('company', 'Preferred methods of delivery'),
            'free_delivery' => Yii::t('company', 'Free delivery'),
            'allegro_link' => Yii::t('company', 'Link to shop on Allegro'),
            'youtube_link' => Yii::t('company', 'Link to Youtube channel'),    
            'business_reliable' => Yii::t('company', 'E-business reliable company'),        
            ),
            array()
            //Item::model()->attributeLabels()           	        	        	    
        );
    }
	
	
    public function getAllegro_link() {
        return !empty($this->allegro)
            ? 'https://allegro.pl/uzytkownik/' . $this->allegro
            : '';
    }

    public function setAllegro_link($value) {
        // extract id from link
        if ($value !== null) {
            if (empty($value)) $this->allegro = null;
            else {
                preg_match(self::$allegroLinkPattern, $value, $matches);
                if (isset($matches[1])) {
                    $this->allegro = $matches[1];
                }
            }
        }
    }
    
    public function getAllegro_link_old() {
        return !empty($this->allegro)
            ? 'http://allegro.pl/listing/user/listing.php?us_id=' . $this->allegro
            : '';
    }
	
    public function setAllegro_link_old($value) {
        // extract id from link
        if ($value !== null) {
            if (empty($value)) $this->allegro = null;
            else {
                preg_match(self::$allegroLinkPattern, $value, $matches);
                if (isset($matches[2])) {
                    $this->allegro = $matches[2];
                }
            }
        }
    }
    
    public function getYoutube_link() {
        return /*!empty($this->youtube_link)
            ? $this->youtube_link
            : */!empty($this->youtube)
                ? 'https://www.youtube.com/user/' . $this->youtube
                : null;
    }

    public function setYoutube_link($value) {
        // extract Youtube id from link
        if ($value !== null) {
            if (empty($value)) $this->youtube = null;
            else {
                preg_match(self::$youtubeLinkPattern, $value, $matches);
                //var_dump($matches);exit();
                if (isset($matches[1])) {
                    $this->youtube = $matches[1];
                }
            }
        }
    }
    
    public function beforeSave_old()
    {
        if ($this->isNewRecord) {
            $item = new Item('create');
            $item->cache_type = 'c';
            $item->save();
            
            $this->item_id = $item->id;
        }
        
        return true;
    }
    
    public function afterSave()
    {
    
    	if ($this->isNewRecord) {
            //echo '<br/>'.'nowy rekord';
            $item = Item::model()->findByPk($this->item_id);

            $alertItemType = Alert::ITEM_TYPE_ITEM;    		

            // powiadomienia dla obserwowanych    
            // pobieramy listę użytkowników obserwujących użytkownika, który dodał firmę
            $followRows = Follow::model()->findAll(
                            'item_id=:item_id and item_type=:item_type',
                            array(':item_id'=>$item->user_id, ':item_type'=>Follow::ITEM_TYPE_USER)
            );
            if($followRows) {
                    foreach($followRows as $follow) {
                            $alert = new Alert;
                            $alert->user_id = $follow->user_id;
                            $alert->context_id = $item->user_id;
                            $alert->context_type = Alert::CONTEXT_TYPE_USER;
                            $alert->item_id = $this->item_id;
                            $alert->item_type = $alertItemType;
                            $alert->event = Alert::EVENT_ADD;
                            $alert->save();
                            unset($alert);
                    }
            }

            // pobieramy listę użytkowników obserwujących kategorię, do której dodano firmę
            $followRows = Follow::model()->findAll(
                            'item_id=:item_id and item_type=:item_type',
                            array(':item_id'=>$item->category_id, ':item_type'=>Follow::ITEM_TYPE_CATEGORY)
            );
            if($followRows) {
                    foreach($followRows as $follow) {
                            $alert = new Alert;
                            $alert->user_id = $follow->user_id;
                            $alert->context_id = $item->category_id;
                            $alert->context_type = Alert::CONTEXT_TYPE_CATEGORY;
                            $alert->item_id = $this->item_id;
                            $alert->item_type = $alertItemType;
                            $alert->event = Alert::EVENT_ADD;
                            $alert->save();
                            unset($alert);
                    }
            }
    	}
    
    	return true;
    }
    
    public function companiesListArray($user_id, $query)
    {
        $limit = 30;
        
        $db = Yii::app()->db;
        
        $companies = $db->createCommand()
                ->select('id, name as text')
                ->from('tbl_item')
                ->where('cache_type=\'c\' '
                        . 'and user_id=:user_id '
                        . 'and name like :name', 
                        array(
                            ':user_id'=>$user_id,
                            ':name'=>'%'.$query.'%')
                        )
                ->limit($limit)
                ->queryAll();
        
        // 'empty' option
        $companies = array_merge(
                array(array('id'=>null, 'text'=>Yii::t('companies', 'none'))),
                $companies
                );
        
        // more than limit
        $companiesCount = $db->createCommand()
                ->select('count(*)')
                ->from('tbl_item')
                ->where('cache_type=\'c\' '
                        . 'and user_id=:user_id '
                        . 'and name like :name',
                        array(
                            ':user_id'=>$user_id,
                            ':name'=>'%'.$query.'%')
                        )
                ->queryScalar();
        if ($companiesCount > $limit) {
            $companies []= array(
                'text'=>($companiesCount-$limit).' '.Yii::t('companies', 'more').'...'
                );
        }
        
        return $companies;
    }
    
	public static function userCompaniesArray($user_id)
    {        
        $db = Yii::app()->db;
        
        $companies = $db->createCommand()
                ->select('id, name')
                ->from('tbl_item')
                ->where('cache_type=\'c\' '
                        . 'and user_id=:user_id',                         
                        array(
                            ':user_id'=>$user_id                        
                        ))                
                ->queryAll();
        
        // 'empty' option
        $companies = array_merge(
                array(array('id'=>0, 'name'=>Yii::t('common', 'select'))),
                $companies
                );        
                       
        return $companies;        
    }
    
	public function adminSearch()
	{
		$criteria = new CDbCriteria;
        
        $criteria->compare('item_id', $this->item_id);
        $criteria->together  =  true;        
        $criteria->with = array('item', 'item.user', 'item.package');
        //$criteria->with = array('item', 'user');
        if($this->item_name)
        {        
        	$criteria->compare('item.name', $this->item_name, true);
        }
		if($this->item_date)
        {        
        	$criteria->compare('item.date', $this->item_date, true);
        }
		if($this->user_username)
        {        
        	$criteria->compare('user.username', $this->user_username, true);
        }
		if($this->package_id)
        {        
        	$criteria->compare('package.id', $this->package_id, true);
        }
        // Create a custom sort
	    $sort=new CSort;
	    $sort->attributes=array(
	      'item_id',
	      // For each relational attribute, create a 'virtual attribute' using the public variable name
	      'item_name' => array(
	        'asc' => 'item.name',
	        'desc' => 'item.name DESC',
	        'label' => 'Nazwa',
	      ),
	      'user_username' => array(
	        'asc' => 'user.username',
	        'desc' => 'user.username DESC',
	        'label' => 'Użytkownik',
	      ),
	       'package_id' => array(
	        'asc' => 'package.id',
	        'desc' => 'package.id DESC',
	        'label' => 'Pakiet',
	      ),
	      'package_expire' => array(
	        'asc' => 'user.package_expire',
	        'desc' => 'user.package_expire DESC',
	        'label' => 'Wygasa',
	      ),
	      'item_date' => array(
	        'asc' => 'item.date',
	        'desc' => 'item.date DESC',
	        'label' => 'Dodana',
	      ),      
	      '*',
	    );
	    $sort->defaultOrder = 'item_id DESC, item.cache_package_id DESC';
	    //$sort->defaultOrder = 'date DESC, package_id DESC';
        
        
		return new CActiveDataProvider('Company', array(
			'criteria'=>$criteria,
			'sort'=>$sort,		
			'pagination' => array( 
                'pageSize' => 50 ,
            ),            
        ));
        
    }
    
	public function companyDataProvider($companyId, $type, $limit=false, $active=true)
    {
	    switch($type) {
	        case 'p':	            
	            $with = 'product';	            
	            break;
	        case 's':
	            $with = 'service';            
	            break;	        
	    }
    	$additionalCriteria = array();
    	if($limit)
    		$additionalCriteria['limit'] = $limit;

    	return new CActiveDataProvider('Item', array(
            'criteria' => array_merge(array(
                'alias' => 'i',
                //'select' => array('id', 'name', 'alias',
                //    'thumbnail_file_id', 'cache_package_id'),
                'with' => array(
                    $with => array(
                        'alias' => 'p',
                    ),
                    'thumbnail',
                    //'item.package'
                ),
                'condition' => 
	                'p.company_id=:company_id '                
	              . ($active ? 'AND i.active=1' : '')
	              . " AND i.cache_type='".$type."'",
                'params' => array(
                    ':company_id' => $companyId,                    
                ),
                //'limit' => $limit,
                'order' => 'i.date DESC',
                ),
                $additionalCriteria
                ),
            'pagination' => false,
            )
        );	
    		
        /*return new CActiveDataProvider(ucfirst($type), array(
            'criteria' => array_merge(array(
                'alias' => 'p',
                //'select' => array('id', 'name', 'alias',
                //    'thumbnail_file_id', 'cache_package_id'),
                'with' => array(
                    'item' => array(
                        'alias' => 'i',
                    ),
                    'item.thumbnail',
                    'item.package'
                ),
                'condition' => 'p.company_id=:company_id '                
                . 'AND i.active=1',
                'params' => array(
                    ':company_id' => $companyId,                    
                ),
                //'limit' => $limit,
                'order' => 'i.date DESC',
                ),
                $additionalCriteria
                ),
            'pagination' => false,
            )
        );*/
    }
    
    public function productsCount()
    {
    	$ilosc = Product::model()->with('item')->count(
    		'company_id=:company_id and active=1',
    		array(':company_id'=>$this->item_id)
    	);
    	echo '<br/>'.$ilosc.'<br/>';
    }
    
    public function newsDataProvider($limit=false, $active=true)
    {
    	$criteria = new CDbCriteria;
    	$criteria->condition = "item_id = ".$this->item_id;
    	if($limit)
    		$criteria->limit = $limit;
    	
    	$sort=new CSort;
    	$sort->defaultOrder = 'date DESC';
    	 
    	return new CActiveDataProvider('News', array(
    			'criteria'=>$criteria,
    			'sort'=>$sort    			
    	));
    }
    
    public function isUeNip()
    {
        if(!$this->nip)
            return false;         
        if(strlen($this->nip) == 12 && preg_match('/^([a-zA-Z]{2})(.*)$/', $this->nip, $nipMatches))
            return $nipMatches[1];                
        return false;
    }        
}
