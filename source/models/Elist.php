<?php

class Elist extends ActiveRecord
{   	
	// Rodzaje list.
	const TYPE_FAVORITE = 1;
	const TYPE_ELIST = 2;
	
	// Rodzaje elementów na listach.
	const ITEM_TYPE_ITEM = 1;
	const ITEM_TYPE_NEWS = 2;
	const ITEM_TYPE_USER = 3;
	
	public $name;
	public $cache_type;
		
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
        return '{{elist}}';
    }
    
	/**
     * Nazwa kolumny głównej.
     * @return string
     */
	public function primaryKey()
    {
    	//return 'package_id';
        return array('user_id', 'item_id', 'type', 'item_type');    // because db has set clustered index
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(
        	'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        	'item' => array(self::BELONGS_TO, 'Item', 'item_id'),            
        	//'news' => array(self::BELONGS_TO, 'News', 'item_id'),
        );
    }
    
    public function rules()
    {
        return array(        	
        	array('user_id, item_id, type, item_type', 'safe'),
        );
    }   
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    /*public function attributeLabels() {
    	return array(
    			'name' => Yii::t('common', 'Name'),   
    			'date' => Yii::t('common', 'Date')
    	);
    }*/
    
    public function afterSave() {
        if ($this->isNewRecord && $this->type == self::TYPE_FAVORITE) {
            $alert = new Alert;
            if($this->item_type == self::ITEM_TYPE_ITEM)
            {    
                $alert->user_id = $this->item->user->id;
                $alert->item_type = Alert::ITEM_TYPE_ITEM;
            } else 
            {    
                $alert->user_id = $this->item_id;
                $alert->item_type = Alert::ITEM_TYPE_USER;
            }    
            $alert->context_id = $this->user_id;
            $alert->context_type = Alert::CONTEXT_TYPE_USER;
            $alert->item_id = $this->item_id;            
//            $alert->item_type = $this->item_type;
            $alert->event = Alert::EVENT_LIKE;
            $alert->save();
//            unset($alert);
        }    
        parent::afterSave();
    }
    
	public function elistDataProvider()
	{
		$criteria = new CDbCriteria;
                
        /*$criteria->together  =  true;        
        $criteria->with = array('item');*/
		$criteria->join = "INNER JOIN tbl_item item on item.id = t.item_id";
        
		$criteria->condition = "t.user_id = ".$this->user_id;
        //$criteria->compare('user_id', $this->user_id);
        
        if($this->name)
        {        
        	$criteria->compare('item.name', $this->name, true);
        }		
		if($this->type)
        {        
        	//echo 'typ z modelu: '.$this->type;
        	$criteria->compare('type', $this->type);
        }
		if($this->cache_type)
        {        
        	$criteria->compare('item.cache_type', $this->cache_type);
        }
		
        // Create a custom sort
	    $sort=new CSort;
	    $defaultOrder = ', t.date DESC';
	    $sort->attributes=array(	      
	      // For each relational attribute, create a 'virtual attribute' using the public variable name
	      'name' => array(
	        'asc' => 'item.name'.$defaultOrder,
	        'desc' => 'item.name DESC'.$defaultOrder,
	        'label' => 'Nazwa',
	      ),
	      'cache_type' => array(
	        'asc' => 'item.cache_type'.$defaultOrder,
	        'desc' => 'item.cache_type DESC'.$defaultOrder,
	        'label' => 'Typ',
	      ),	            
	      'date' => array(
	        'asc' => 't.date',
	        'desc' => 't.date DESC',
	        'label' => 'Dodano',
	      ),
	      '*',
	    );
	    //$sort->defaultOrder = 'item_id DESC, item.cache_package_id DESC';
	    $sort->defaultOrder = 't.date DESC';
        
        
		return new CActiveDataProvider('Elist', array(
			'criteria'=>$criteria,
			'sort'=>$sort,		
			'pagination' => array( 
                'pageSize' => 20 ,
            ),            
        ));
        
    }
    
    public function elistDataProviderUnion()
    {
//    	echo '<br>elistDataProviderUnion()';
//     	$sql = '(SELECT e.item_id as id, i.name as name, i.cache_type, e.item_type, e.date'.
//     		' FROM tbl_item i inner join tbl_elist e on e.item_id=i.id where e.user_id=22 and e.type=1 and e.item_type=1)'.
//     		' UNION ALL'.
//     		' (SELECT e.item_id as id, u.username as name, \'u\' as cache_type, e.item_type, e.date'.
//     		' FROM tbl_user u inner join tbl_elist e on e.user_id=u.id where e.user_id=22 and e.type=1 and e.item_type=3)';
    	
//     	$sql = '(SELECT e.item_id as id, e.item_type, i.name as name, i.cache_type, i.alias, e.date'.
//     			' FROM tbl_elist e inner join tbl_item i on i.id=e.item_id where e.user_id='.$this->user_id.' and e.type='.self::TYPE_FAVORITE.' and e.item_type='.self::ITEM_TYPE_ITEM.')'.
//     			' UNION ALL'.
//     			' (SELECT e.item_id as id, e.item_type, u.username as name, \'u\' as cache_type, \'\' as alias, e.date'.
//     			' FROM tbl_elist e inner join tbl_user u on u.id=e.item_id where e.user_id='.$this->user_id.' and e.type='.self::TYPE_FAVORITE.' and e.item_type='.self::ITEM_TYPE_USER.')';
    	
    	$sql = '(SELECT e.item_id as id, e.item_type, i.name as name, i.cache_type, i.alias, e.date'.
      			', f.class, f.data_id, f.hash, f.extension'.
    			' FROM tbl_elist e inner join tbl_item i on i.id=e.item_id'.
    			' left join tbl_file f on f.id=i.thumbnail_file_id where e.user_id='.$this->user_id.' and e.type='.$this->type.' and e.item_type='.self::ITEM_TYPE_ITEM.')'.
    			' UNION ALL'.
    			' (SELECT e.item_id as id, e.item_type, u.username as name, \'u\' as cache_type, \'\' as alias, e.date'.
    			', f.class, f.data_id, f.hash, f.extension'.
    			' FROM tbl_elist e inner join tbl_user u on u.id=e.item_id'.
    			' left join tbl_file f on f.id=u.thumbnail_file_id where e.user_id='.$this->user_id.' and e.type='.$this->type.' and e.item_type='.self::ITEM_TYPE_USER.')';
    	
//     	echo $sql.'<br />'; 
    	$count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM ('.$sql.') AS count_alias')->queryScalar();
    	
    	// Create a custom sort
    	$sort=new CSort;
    	$defaultOrder = ', date DESC';
    	$sort->attributes=array(
    			// For each relational attribute, create a 'virtual attribute' using the public variable name
    			'name' => array(
    					'asc' => 'name'.$defaultOrder,
    					'desc' => 'name DESC'.$defaultOrder,
    					'label' => Yii::t('elists', 'Name'),
    			),
    			'cache_type' => array(
    					'asc' => 'cache_type'.$defaultOrder,
    					'desc' => 'cache_type DESC'.$defaultOrder,
    					'label' => Yii::t('elists', 'Type'),
    			),
    			'date' => array(
    					'asc' => 'date',
    					'desc' => 'date DESC',
    					'label' => Yii::t('elists', 'Added'),
    			),
    			'*',
    	);
    	//$sort->defaultOrder = 'item_id DESC, item.cache_package_id DESC';
    	$sort->defaultOrder = 'date DESC';

		return new CSqlDataProvider($sql, array(
		    'totalItemCount'=>$count,
		    'sort'=> $sort,
		    /*array(
		        'attributes'=>array(		        		
		             'cache_type', 'name', 'date',
		        ),
		    ),*/
		    'pagination'=>array(
		        'pageSize'=>40,
		    ),
		));
    
    }
    
    public function elistDataProviderUnion_old()
    {
    	$sql = '(SELECT e.item_id as id, i.name as name, i.cache_type, e.item_type, e.date'.
    	    		' FROM tbl_item i inner join tbl_elist e on e.item_id=i.id where e.user_id=22 and e.type=1 and e.item_type=1)'.
    	    		' UNION ALL'.
    	    		' (SELECT e.item_id as id, u.username as name, \'u\' as cache_type, e.item_type, e.date'.
    	    		' FROM tbl_user u inner join tbl_elist e on e.user_id=u.id where e.user_id=22 and e.type=1 and e.item_type=3)';
    	 
    	 
    	$count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM ('.$sql.') AS count_alias')->queryScalar();
    	//     	echo $count.'<br />';
    	 
    	// Create a custom sort
    	$sort=new CSort;
    	$defaultOrder = ', date DESC';
    	$sort->attributes=array(
    			// For each relational attribute, create a 'virtual attribute' using the public variable name
    			'name' => array(
    					'asc' => 'name'.$defaultOrder,
    					'desc' => 'name DESC'.$defaultOrder,
    					'label' => Yii::t('elists', 'Name'),
    			),
    			'cache_type' => array(
    					'asc' => 'cache_type'.$defaultOrder,
    					'desc' => 'cache_type DESC'.$defaultOrder,
    					'label' => Yii::t('elists', 'Type'),
    			),
    			'date' => array(
    					'asc' => 'date',
    					'desc' => 'date DESC',
    					'label' => Yii::t('elists', 'Added'),
    			),
    			'*',
    	);
    	//$sort->defaultOrder = 'item_id DESC, item.cache_package_id DESC';
    	$sort->defaultOrder = 'date DESC';
    	 
    	return new CSqlDataProvider($sql, array(
    			'totalItemCount'=>$count,
    			'sort'=> $sort,
    			/*array(
    			 'attributes'=>array(
    			 		'cache_type', 'name', 'date',
    			 ),
    			),*/
    			'pagination'=>array(
    					'pageSize'=>40,
    			),
    	));
    
    }
    
    public function elistInverseDataProvider_old()
    {
    	$criteria = new CDbCriteria;    	
    	
    	$criteria->join = "INNER JOIN tbl_user user on user.id = t.user_id";
    	
// 		$criteria->condition = "t.type = ".$this->type;
//     	$criteria->compare('user_id', $this->user_id);
    	$criteria->compare('item_id', $this->item_id);
    	$criteria->compare('item_type', $this->item_type);
    	$criteria->compare('t.type', $this->type);
    	
    	$sort=new CSort;
    	$sort->defaultOrder = 't.date DESC';
    	
    	
    	return new CActiveDataProvider('Elist', array(
    		'criteria'=>$criteria,
    		'sort'=>$sort    		
    	));
    }
    
    /*
     * lista uzytkownikow, do ktorych zostnaie wyslane powiadomienie, o dodaniu przez uzytkownika lub firme FPU
     */
    public function inverseRecipientsDataProvider()
    {
//        u.id, u.username, u.forename, u.surname, u.email, u.language
        $sql = '(SELECT t.id, t.username, t.forename, t.surname, t.email, t.language'.            
            ' FROM tbl_user t INNER JOIN tbl_elist e on e.user_id=t.id'.
//            ' LEFT JOIN tbl_file f on f.id=t.thumbnail_file_id where e.item_id='.$this->item_id.' and e.type='.$this->type.' and e.item_type='.$this->item_type.')';
            ' where e.item_id='.$this->item_id.' and e.item_type='.$this->item_type.')';
        return new CSqlDataProvider($sql);
    }
    
    public function elistInverseDataProvider()
    {
//    	echo '<br>elistInverseDataProvider()'; 
    	$sql = '(SELECT t.id as id, t.username as name'.
    			', f.class, f.data_id, f.hash, f.extension, '.self::ITEM_TYPE_USER.' as item_type'.
    			' FROM tbl_user t INNER JOIN tbl_elist e on e.user_id=t.id'.
//     			' LEFT JOIN tbl_file f on f.id=t.thumbnail_file_id where e.item_id='.$this->item_id.' and e.type='.$this->type.' and e.item_type='.$this->item_type.')';
    			' LEFT JOIN tbl_file f on f.id=t.thumbnail_file_id where e.item_id='.$this->item_id.' and e.type='.$this->type.' and e.item_type='.$this->item_type.')';
    			
//     	$criteria = new CDbCriteria;
    	 
//     	$criteria->mergeWith(array(
//     			'join' => 'INNER JOIN tbl_elist e ON e.user_id=t.id LEFT JOIN tbl_file uf ON uf.id=t.thumbnail_file_id'
//     	));
//     	$criteria->compare('e.item_id', $this->item_id);
//     	$criteria->compare('e.item_type', $this->item_type);
//     	$criteria->compare('e.type', $this->type);
    	
    	
    	//     	echo $sql.'<br />';
    	$count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM ('.$sql.') AS count_alias')->queryScalar();
    	 
    	// Create a custom sort
//     	$sort=new CSort;
//     	$defaultOrder = ', date DESC';
//     	$sort->attributes=array(
//     			// For each relational attribute, create a 'virtual attribute' using the public variable name
//     			'name' => array(
//     					'asc' => 'name'.$defaultOrder,
//     					'desc' => 'name DESC'.$defaultOrder,
//     					'label' => Yii::t('elists', 'Name'),
//     			),
//     			'cache_type' => array(
//     					'asc' => 'cache_type'.$defaultOrder,
//     					'desc' => 'cache_type DESC'.$defaultOrder,
//     					'label' => Yii::t('elists', 'Type'),
//     			),
//     			'date' => array(
//     					'asc' => 'date',
//     					'desc' => 'date DESC',
//     					'label' => Yii::t('elists', 'Added'),
//     			),
//     			'*',
//     	);
//     	//$sort->defaultOrder = 'item_id DESC, item.cache_package_id DESC';
//     	$sort->defaultOrder = 'date DESC';
    
    	$sort=new CSort;
    	$sort->defaultOrder = 'e.date DESC';
    	
    	return new CSqlDataProvider($sql, array(
            'totalItemCount'=>$count,
//     			'sort'=> $sort,
            /*array(
             'attributes'=>array(
                            'cache_type', 'name', 'date',
             ),
            ),*/
            'pagination'=>array(
                'pageSize'=>40,
            ),
//     			'criteria'=>$criteria,
            'sort'=>$sort
    	));
    
    }
    
    public function elistInverseDataProvider1()
    {
    	echo '<br>Elist->elistInverseDataProvider';
    	echo '<br>$this->item_id: '.$this->item_id;
    	echo '<br>$this->item_type: '.$this->item_type;
    	echo '<br>$this->type: '.$this->type;
    	
    	$criteria = new CDbCriteria;
    	
    	$criteria->mergeWith(array(
    			'join' => 'INNER JOIN tbl_elist el ON el.user_id=t.id LEFT JOIN tbl_file uf ON uf.id=t.thumbnail_file_id'
    	));
//     	$criteria->select = 't.id, t.username, uf.class, uf.data_id, uf.hash, uf.extension';
    	
//     	, f.class, f.data_id, f.hash, f.extension
// 		$criteria->select = 't.id, t.username, thumbnail.class, f.data_id, f.hash, f.extension';
//     	$criteria->join = "INNER JOIN tbl_elist elist ON t.id = elist.user_id LEFT JOIN tbl_file f ON f.id=t.thumbnail_file_id";
// 		$criteria->with = array('UserFile');
// 		$criteria->with = array('elists', 'thumbnail');
		$criteria->with = array('thumbnail');
//     	$criteria->join = 'left join tbl_file f on f.id=t.thumbnail_file_id'.
    	// 		$criteria->condition = "t.type = ".$this->type;
    	//     	$criteria->compare('user_id', $this->user_id);
    	$criteria->compare('el.item_id', $this->item_id);
    	$criteria->compare('el.item_type', $this->item_type);
    	$criteria->compare('el.type', $this->type);
    	 
    	$sort=new CSort;
    	$sort->defaultOrder = 'el.date DESC';

    	$provider = new CActiveDataProvider('User', array(
    			'criteria'=>$criteria,
    			'sort'=>$sort
    	));
    	
//     	var_dump($provider->)
    	 
    	return $provider;
    }
    
    /*
     * zwraca klasę ikonki fa dla zadanego typu obiektu
     * '<i class="fa fa-'.Elist::faIconClass ...
     */
    public static function faIconClass($itemType)
    {
    	switch($itemType) {
    		case 'c':
    			return 'building-o';
    		case 's':
    			return 'truck';
    		case 'p':
    			return 'shopping-cart';
    		case 'u':	
    		default:
    			return 'user';
    	}
    }
    
    public function elistFaIconClass()
    {
    	switch($this->type) {
    		case self::TYPE_ELIST:
    			return 'fa-list';    		
    		default:
    			return 'fa-heart';
    	}
    }
    
    public function inverseListName()
    {
    	switch($this->type) {
    		case self::TYPE_ELIST:
    			return Yii::t('elists', 'Added to elist');
    		default:
    			return Yii::t('elists', 'Added to favorites');
    	}
    }
    
    public function elistName()
    {
    	switch($this->type) {
    		case self::TYPE_ELIST:
    			return Yii::t('elists', 'Elist');
    		default:
    			return Yii::t('elists', 'Favorite');
    	}
    }
    
    /*
     * lista elementów na elistach 
     * wykorzystana podczas wyswietlania przyciskow dodania/usuniecia do elisty
     */
    public static function userItems($user_id) {
    	/*$items = self::model()->findAll(
    		'user_id=:user_id',
    		array(':user_id'=>$user_id)    		
    	);*/
    	$db = Yii::app()->db;
    	$items = $db->createCommand()
                ->select('item_id, type')
                ->from('tbl_elist')
                ->where('user_id=:user_id',
                        array(':user_id'=>$user_id))
                ->queryAll();
        
       	$result = array('favorite'=>array(), 'elist'=>array());
       	foreach($items as $item){
       		if($item['type'] == Elist::TYPE_FAVORITE && !in_array($item['item_id'], $result['favorite']))
       			$result['favorite'][] = $item['item_id'];
       		elseif($item['type'] == Elist::TYPE_ELIST && !in_array($item['item_id'], $result['elist']))
       			$result['elist'][] = $item['item_id'];	
       	}       	
       	
       	return $result;
    }
    
    public static function itemLink($item) {
    
    	if($item['item_type'] == self::ITEM_TYPE_ITEM) {
    		
    		switch($item['cache_type']) {
    			case 'p':
    				$url_part = 'products';
    				break;
    			case 's':
    				$url_part = 'services';
    				break;
    			default:
    				$url_part = 'companies';    				
    		}
    		return CHtml::link($item['name'], Yii::app()->createUrl("$url_part/show",
    				array("name"=>$item['alias'])));
    
    	} elseif($item['item_type'] == self::ITEM_TYPE_USER) {
    
    		return CHtml::link($item['name'], Yii::app()->createUrl("user/profile",
    				array("username"=>$item['name'])));
    
    	}
    }
    
    public static function itemUrl($item) {
    
    	if($item['item_type'] == self::ITEM_TYPE_ITEM) {
    
    		switch($item['cache_type']) {
    			case 'p':
    				$url_part = 'products';
    				break;
    			case 's':
    				$url_part = 'services';
    				break;
    			default:
    				$url_part = 'companies';
    		}
    		return Yii::app()->createUrl("$url_part/show",
    				array("name"=>$item['alias']));
    
    	} elseif($item['item_type'] == self::ITEM_TYPE_USER) {
    
    		return Yii::app()->createUrl("user/profile",
    				array("username"=>$item['name']));
    
    	}
    }
    
    public function getPageName() {
    	if($this->type == self::TYPE_ELIST)
    		return Yii::t('elists', 'Elist');
    	return Yii::t('elists', 'Favorite');
    }
}
?>