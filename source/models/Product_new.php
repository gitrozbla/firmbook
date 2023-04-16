<?php
/**
 * Model produktu.
 * 
 * @category models
 * @package product
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Product extends ActiveRecord
{
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
        return '{{product}}';
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
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
        );
    }
    
    public function rules()
    {
        return array(
            // update
            //array('company_id', 'required', 'on'=>'update, create'),
            array('company_id', 'integrationValidate', 'on'=>'update, create'),
            array('short_description', 'length', 'max'=>250, 'on'=>'update, create'),
            array('signature', 'length', 'max'=>50, 'on'=>'update, create'),
            array('youtube_url', 'length', 'max'=>100, 'on'=>'update, create'),   
			array('price, promotion_price, delivery_price', 'match', 
				'pattern' => '/^[1-9]{1}\d{0,9}(?:[\.,]{1}\d{1,2})?$|^[0]{1}(?:[\.,]{1}\d{1,2})?$/',
				//'pattern' => '/^[1-9]{1}\d{0,9}([\.,]{1}\d{1,2}){0,1}$|^[0]{1}([\.,]{1}\d{1,2}){0,1}$/', 
				'message' => 'Podaj właściwą cenę', "on"=>"update, create"),            
            array('price, promotion_price, delivery_price', 'length', 'max'=>13, 'on'=>'update, create'),
            array('currency_id', 'numerical', 'on'=>'update, create'),
            //array('currency_id', 'numerical', 'min'=>1, 'on'=>'update, create'),            
			array('unit_id', 'numerical', 'on'=>'update, create'),			
            array('promotion_expire', 'type', 'type'=>'date', 'dateFormat'=>'MM/dd/yyyy', 'on'=>'update, create'),
           	//array('promotion_expire', 'type', 'type'=>'date', 'dateFormat'=>'yyyy-MM-dd', 'on'=>'update, create'),
            array('delivery_free', 'boolean', 'on'=>'update, create'),  
           	array('adults', 'boolean', 'on'=>'update, create'),            
            
            array('delivery_min, delivery_time', 'safe'),
            );
    }
    
	/**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
        	'company_id' => Yii::t('product', 'Offering company'),
        	'short_description' => Yii::t('common', 'Short description'),
        	'signature' => Yii::t('product', 'Signature'),
        	'youtube_url' => Yii::t('product', 'Youtube movie'),
        	'price' => Yii::t('common', 'Price'),
        	'currency_id' => Yii::t('common', 'Currency'),
        	'unit_id' => Yii::t('product', 'Unit'),
        	'promotion_price' => Yii::t('product', 'Promotion price'),
        	'promotion_expire' => Yii::t('product', 'Promotion expire date'),
        	'delivery_free' => Yii::t('product', 'Free delivery'),
        	'delivery_price' => Yii::t('product', 'Delivery price'),
        	'delivery_min' => Yii::t('product', 'Delivery min'),
        	'delivery_time' => Yii::t('product', 'Delivery time'),
        	'adults' => Yii::t('product', 'Adults only'),
        	           	        	        	    
        );
    }
    
    public function integrationValidate($attribute)
    {
        switch($attribute) {
            case 'company_id':
                // company exists and has the same user
                if ($this->company_id != 0 && !Item::model()->exists(
                        'id=:id and cache_type=\'c\' and user_id=:user_id', 
                        array(
                            ':id'=>$this->company_id,
                            //':user_id'=>$this->item->user_id
                            ':user_id'=>Yii::app()->user->id))
                        ) {
                    $this->addError($attribute, Yii::t('companies', 'Company does not exists.'));
                }
                break;
        }
            
    }
    
	public function beforeSave()
    {        
    	if (empty($this->promotion_expire)) 			
	        $this->promotion_expire = NULL;	    
    	
		if(!empty($this->promotion_expire))
			$this->promotion_expire = Yii::app()->dateFormatter->format('yyyy-MM-dd', $this->promotion_expire);
				       
		if(!empty($this->price))
			$this->price = str_replace(',', '.', $this->price);	
			
		if(!empty($this->promotion_price))
			$this->promotion_price = str_replace(',', '.', $this->promotion_price);	
			
		if(!empty($this->delivery_price))
			$this->delivery_price = str_replace(',', '.', $this->delivery_price);	
			
        return true;
    }
    
	public function afterSave()
    {        
		if(!empty($this->promotion_expire))
			$this->promotion_expire = Yii::app()->dateFormatter->format('MM/dd/yyyy', $this->promotion_expire);
			       
        return true;
    }
    
    public function beforeSave_org()
    {
        if ($this->isNewRecord) {
            $item = new Item('create');
            $item->cache_type = 'p';
            $item->save();
            
            $this->item_id = $item->id;
        }
        
        return true;
    }
    
	
    
}
