<?php

class Follow extends ActiveRecord
{   
    // Rodzaj elementu/tabeli.
    const ITEM_TYPE_COMPANY = 1;
    const ITEM_TYPE_USER = 2;
    const ITEM_TYPE_CATEGORY = 3;

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
        return '{{follow}}';
    }
    
	/**
     * Nazwa kolumny głównej.
     * @return string
     */
	public function primaryKey()
    {
    	//return 'package_id';
        return array('user_id', 'item_id', 'item_type');    // because db has set clustered index
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(
        	'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        	//'item' => array(self::BELONGS_TO, 'Item', 'item_id'),            
        	//'news' => array(self::BELONGS_TO, 'News', 'item_id'),
        );
    }
    
    public function rules()
    {
        return array(        	
        	array('user_id, item_id, item_type', 'safe'),
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
    
	
    
    public function followDataProviderUnion()
    {
    	$sql = '(SELECT f.item_id as id, f.item_type, f.date'.
    			', i.name, i.cache_type, i.alias'.
    			', fi.class, fi.data_id, fi.hash, fi.extension'.
    			' FROM  tbl_follow f'.
    			' inner join tbl_item i on i.id=f.item_id'.
    			' left join tbl_file fi on fi.id=i.thumbnail_file_id'.
    			' where f.user_id='.$this->user_id.' and f.item_type='.self::ITEM_TYPE_COMPANY.')'.
    			' UNION ALL'.
    			' (SELECT f.item_id as id, f.item_type, f.date'.
    			', u.username as name, \'u\' as cache_type, \'\' as alias'.
    			', fi.class, fi.data_id, fi.hash, fi.extension'.
    			' FROM tbl_follow f'.
    			' inner join tbl_user u on u.id=f.item_id'.
    			' left join tbl_file fi on fi.id=u.thumbnail_file_id'.
    			' where f.user_id='.$this->user_id.' and f.item_type='.self::ITEM_TYPE_USER.')'.
		    	' UNION ALL'.
		    	' (SELECT f.item_id as id, f.item_type, f.date'.
		    	', c.name, \'k\' as cache_type, c.alias'.
		    	',\'\' as class,\'\' as data_id,\'\' as hash,\'\' as extension'.
		    	' FROM tbl_follow f'.
		    	' inner join tbl_category c on c.id=f.item_id'.
		    	' where f.user_id='.$this->user_id.' and f.item_type='.self::ITEM_TYPE_CATEGORY.')';
    	
    	/*$sql = '(SELECT e.item_id as id, i.name as name, i.cache_type, i.alias, e.item_type, e.date'.
    		' FROM tbl_item i inner join tbl_follow e on e.item_id=i.id where e.user_id=22 and e.item_type=1)'.
    		' UNION ALL'.
    		' (SELECT e.item_id as id, u.username as name, \'u\' as cache_type, \'\' as alias, e.item_type, e.date'.
    		' FROM tbl_user u inner join tbl_follow e on e.user_id=u.id where e.user_id=22 and e.item_type=2)';*/
    	
    	$count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM ('.$sql.') AS count_alias')->queryScalar();
    	//echo $count.'<br />'; 
    	
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
    
    /*
     * lista uzytkownikow, do ktorych zostnaie wyslane powiadomienie, o dodaniu przez uzytkownika lub firme FPU
     */
    public function inverseRecipientsDataProvider()
    {
        $sql = '(SELECT t.id, t.username, t.forename, t.surname, t.email, t.language'.                        
            ' FROM tbl_user t INNER JOIN tbl_follow e on e.user_id=t.id'.
            ' WHERE e.item_id='.$this->item_id.' and e.item_type='.$this->item_type.')';
        return new CSqlDataProvider($sql);
    }
    
    public function followInverseDataProvider()
    {
    
    	$sql = '(SELECT t.id as id, t.username as name'.
    			', f.class, f.data_id, f.hash, f.extension, '.self::ITEM_TYPE_USER.' as item_type'.
    			' FROM tbl_user t INNER JOIN tbl_follow e on e.user_id=t.id'.
    			//     			' LEFT JOIN tbl_file f on f.id=t.thumbnail_file_id where e.item_id='.$this->item_id.' and e.type='.$this->type.' and e.item_type='.$this->item_type.')';
    	' LEFT JOIN tbl_file f on f.id=t.thumbnail_file_id where e.item_id='.$this->item_id.' and e.item_type='.$this->item_type.')';
    	 
    	//     	$criteria = new CDbCriteria;
    
    	//     	$criteria->mergeWith(array(
    	//     			'join' => 'INNER JOIN tbl_elist e ON e.user_id=t.id LEFT JOIN tbl_file uf ON uf.id=t.thumbnail_file_id'
    	//     	));
    	//     	$criteria->compare('e.item_id', $this->item_id);
    	//     	$criteria->compare('e.item_type', $this->item_type);
    	//     	$criteria->compare('e.type', $this->type);
    	 
    	 
    	//     	echo $sql.'<br />';
    	//     	$count = Yii::app()->db->createCommand('SELECT COUNT(*) FROM ('.$sql.') AS count_alias')->queryScalar();
    
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
    	//     			'totalItemCount'=>$count,
    	//     			'sort'=> $sort,
    			/*array(
    			 'attributes'=>array(
    			 		'cache_type', 'name', 'date',
    			 ),
    			),*/
    	//     			'pagination'=>array(
    			//     					'pageSize'=>40,
    			//     			),
    	//     			'criteria'=>$criteria,
    			'sort'=>$sort
    	));
    
    }
    
           
    
    
    /*
     * zwraca klasę ikonki fa dla zadanego typu śledzonego obiektu
     * '<i class="fa fa-'.Follow::faIconClass ...
     */
    public static function faIconClass($itemType)
    {
    	switch($itemType) {
    		case 'c':
    			return 'building-o';
    		/*case 's':
    			return 'truck';
    		case 'p':
    			return 'shopping-cart';*/
    		case 'k': //kategoria
    			return 'briefcase';
    		case 'u':	
    		default:
    			return 'user';
    	}
    }  
    
    
    public static function itemLink($item) {
    	 
    	if($item['item_type'] == self::ITEM_TYPE_COMPANY) {
    		
    		return CHtml::link($item['name'], Yii::app()->createUrl("companies/show",
    				array("name"=>$item['alias'])));
    		
    	} elseif($item['item_type'] == self::ITEM_TYPE_USER) {
    		
    		return CHtml::link($item['name'], Yii::app()->createUrl("user/profile",
    				array("username"=>$item['name'])));
    		
    	} elseif($item['item_type'] == self::ITEM_TYPE_CATEGORY) {
    		
    		$search = Search::model()->getFromSession();
    		$alias = $item['alias']
		    		? Yii::t('category.alias', $item['alias'], null, 'dbMessages')
		    		: $item['id'];
    		
    		return CHtml::link(
//     				Yii::t('category.name', $item['name'], null, 'dbMessages'), 
    				Yii::t('category.name', $item['alias'], array($item['alias']=>$item['name']), 'dbMessages'),
//     				Yii::app()->createUrl('categories/show', array('name'=>$alias)));
    				$search->createUrl('categories/show', array('name'=>$alias)));
    		/*return CHtml::link($item['name'], Yii::app()->createUrl("products/show",
    				array("name"=>$item['alias'])));*/
    	} 
    }
    
    public static function itemUrl($item) {
    
    	if($item['item_type'] == self::ITEM_TYPE_COMPANY) {
    
    		return Yii::app()->createUrl("companies/show",
    				array("name"=>$item['alias']));
    
    	} elseif($item['item_type'] == self::ITEM_TYPE_USER) {
    
    		return Yii::app()->createUrl("user/profile",
    				array("username"=>$item['name']));
    
    	} elseif($item['item_type'] == self::ITEM_TYPE_CATEGORY) {
    
    		$search = Search::model()->getFromSession();
    		$alias = $item['alias']
    		? Yii::t('category.alias', $item['alias'], null, 'dbMessages')
    		: $item['id'];
    
    		return 	$search->createUrl('categories/show', array('name'=>$alias));    		
    	}
    }
}
?>