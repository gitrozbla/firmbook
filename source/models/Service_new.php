<?php
/**
 * Model usługi.
 * 
 * @category models
 * @package service
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Service extends ActiveRecord
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
        return '{{service}}';
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
            array('company_id', 'integrationValidate', 'on'=>'update, create'),
            );
    }
    
	/**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
        	'company_id' => Yii::t('product', 'Offering company'),
        	           	        	        	    
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
    
    public function beforeSave_org()
    {
        if ($this->isNewRecord) {
            $item = new Item('create');
            $item->cache_type = 's';
            $item->save();
            
            $this->item_id = $item->id;
        }
        
        return true;
    }
    
}
