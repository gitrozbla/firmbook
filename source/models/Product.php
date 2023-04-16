<?php
/**
 * Model produktu.
 * 
 * @category models
 * @package product
 * @author
 * @copyright (C) 2015
 */
class Product extends OfferItem
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
    /*public function primaryKey()
    {
        return 'item_id';    // because db has set clustered index
    }*/
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    /*public function relations()
    {
        return array(
            'item' => array(self::BELONGS_TO, 'Item', 'item_id', 'together'=>true),
            'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
        );
    }*/
    
    public function rules()
    {
        return array_merge(
        	parent::rules(),
        	array(                           
				array('delivery_price', 'match', 
					'pattern' => '/^[1-9]{1}\d{0,9}(?:[\.,]{1}\d{1,2})?$|^[0]{1}(?:[\.,]{1}\d{1,2})?$/',
					//'pattern' => '/^[1-9]{1}\d{0,9}([\.,]{1}\d{1,2}){0,1}$|^[0]{1}([\.,]{1}\d{1,2}){0,1}$/', 
					'message' => 'Podaj właściwą cenę', "on"=>"update, create"),            
	            array('delivery_price', 'length', 'max'=>13, 'on'=>'update, create'),            
	            array('delivery_free', 'boolean', 'on'=>'update, create'),  
	           	array('adults', 'boolean', 'on'=>'update, create'),                        
	            array('delivery_min, delivery_time', 'safe'),
            )          	  
    	);
    }
    
	/**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array_merge(
            parent::attributeLabels(),
            array(	        	
                'delivery_free' => Yii::t('product', 'Free delivery'),
                'delivery_price' => Yii::t('product', 'Delivery price'),
                'delivery_min' => Yii::t('product', 'Delivery min'),
                'delivery_time' => Yii::t('product', 'Delivery time (days)'),
                'adults' => Yii::t('product', 'Adults only'),
                'allegro_link' => Yii::t('product', 'Link to product on Allegro'),
            )        	           	        	        	    
        );
    }
    
    /*public function integrationValidate($attribute)
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
            
    }*/
    
	public function beforeSave()
    {       
		parent::beforeSave();    	
    		
		if(isset($this->delivery_price) && empty($this->delivery_price))
			$this->delivery_price = NULL;
		if(!empty($this->delivery_price))
			$this->delivery_price = str_replace(',', '.', $this->delivery_price);	
			
		if(isset($this->delivery_min) && empty($this->delivery_min))
			$this->delivery_min = NULL;	
			
		if(isset($this->delivery_time) && empty($this->delivery_time))
			$this->delivery_time = NULL;
				
        return true;
    }
    
        
}
