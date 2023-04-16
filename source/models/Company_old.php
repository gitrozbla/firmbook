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
	//wykorzystany w adminSearch 
	public $item_name;
	public $item_date;
	public $user_username;
	public $package_id;
	public $package_expire;
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
            
            'products' => array(self::HAS_MANY, 'Product', 'item_id'),
            'services' => array(self::HAS_MANY, 'Service', 'item_id'),
        	//'user' => array(self::BELONGS_TO, 'User', 'user_id', 'together'=>true),
        	'user' => array(self::HAS_ONE, 'User', array('user_id'=>'id'), 'through'=> 'item'),
        	'package' => array(self::HAS_ONE, 'Package', array('cache_package_id'=>'id'), 'through'=> 'item'),
        );
    }
    
    public function rules()
    {
        return array(
            // update
            array('short_description', 'length', 'max'=>250, 'on'=>'update'),
            array('phone', 'phone', 'on'=>'update'),
            array('phone', 'length', 'max'=>20, 'on'=>'update'),
            array('email', 'email', 'on'=>'update'),
            array('email', 'length', 'max'=>64, 'on'=>'update'),
             // search
            array('item_id, email, phone, item_name, user_username, item_date, package_id', 'safe', 'on'=>'adminSearch'), 
            //array('item_id, email, phone', 'safe', 'on'=>'adminSearch'),
            );
    }
    
    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $item = new Item('create');
            $item->cache_type = 'c';
            $item->save();
            
            $this->item_id = $item->id;
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
    
}
