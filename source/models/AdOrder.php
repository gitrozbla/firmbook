<?php
/**
 * Model zamówienia reklamowego.
 * 
 * @category models
 * @package ad
 * @author 
 * @copyright (C) 
 */
class AdOrder extends ActiveRecord
{
	
	public static $_periods = array(2, 4, 6, 8, 10, 12, 14, 16, 18, 20);
	
	//wykorzystane w adminSearch	
	public $user_username;
	public $box_label;
	
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
        return '{{ad_order}}';
    }
    
    /**
     * Nazwa kolumny głównej.
     * @return string
     */
    public function primaryKey()
    {
        return 'id';    // because db has set clustered index
    }
    
    public function relations()
    {
    	return array(
    		'user' => array(self::BELONGS_TO, 'User', 'user_id'),
    		'box' => array(self::BELONGS_TO, 'AdsBox', 'box_id'),    			
    	);
    }
    
    public function rules()
    {
    	return array(
    		array('box_id, period, price', 'safe'),
    		// search
    		array('box_label, user_username, price, period, date, paid', 'safe', 'on'=>'adminSearch'),
    	);
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
    	return array(
    		'period' => Yii::t('ad', 'Period (weeks)'),  		
    		'price' => Yii::t('ad', 'Price'),
    		'date' => Yii::t('ad', 'Date'),
    		'paid' => Yii::t('ad', 'Paid'),
    	);
    }
    
    public function beforeSave()
    {
    	if ($this->isNewRecord) {
    		
    		$box = AdsBox::model()->findByPk($this->box_id);    		
    		$this->price = ($this->period * $box->price)/$box->period;
    		
    		$this->user_id = Yii::app()->user->id;
    		
    	}
    	
    	return parent::beforeSave();
    }
    
    public function adminSearch()
    {
    	$criteria = new CDbCriteria;
    
    	//$criteria->compare('id', $this->id);
    	$criteria->together  =  true;
    	$criteria->with = array('user', 'box');
    	
    	if($this->user_username)
    	{
    		$criteria->compare('user.username', $this->user_username, true);
    	}
    	if($this->box_label)
    	{
    		$criteria->compare('box.label', $this->box_label, true);
    	}
    	/*if($this->box_id)
    	{
    		$criteria->compare('box_id', $this->box_id, true);
    	}*/
    	// Create a custom sort
    	$sort=new CSort;
    	$sort->attributes=array(
    			//'box.label',
    			'box_label' => array(
    					'asc' => 'box.label',
    					'desc' => 'box.label DESC',
    					'label' => 'Etykieta',
    			),
    			'box.name',    			 
    			'user_username' => array(
    					'asc' => 'user.username',
    					'desc' => 'user.username DESC',
    					'label' => 'Użytkownik',
    			),
    			/*'box_id' => array(
    					'asc' => 't.box_id',
    					'desc' => 't.box_id DESC',
    					'label' => 'Box',
    			),*/    			
    			'*',
    	);
    	$sort->defaultOrder = 't.date DESC, t.box_id DESC';    
    
    	return new CActiveDataProvider('AdOrder', array(
    			'criteria'=>$criteria,
    			'sort'=>$sort,
    			'pagination' => array(
    					'pageSize' => 50 ,
    			),
    	));
    
    }
    
    public static function ordersDataProvider($userId)
    {
    	 $rows = new CActiveDataProvider('AdOrder', array(
    			'criteria'=>array(
    				//'select' => 't.*, ab.name as name, ab.label as label',
    				//'join' => 'INNER JOIN tbl_ad_box ab on ab.id=t.box_id',
    				'condition'=>'t.user_id='.$userId,
    				'order'=>'t.date DESC',
    				'with'=>array('box'),
    			),
    			/*'sort'=>array(
    				'attributes'=>array(
    						'name'=>array(
    								'asc'=>'name',
    								'desc'=>'name DESC'
    						),
    						'*',
    				)
    			),*/
    			'pagination' => false,
    	));
    
    	//print_r($rows->getData()); 
    	 
    	return $rows;
    }
}