<?php

class Dictionary extends ActiveRecord
{    
	public static $types = array(
		'currency' => 1,
		'unit' => 2
	);
	
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
        return '{{dictionary}}';
    }
    
/**
     * Nazwa kolumny głównej.
     * @return string
     */
	public function primaryKey()
    {
    	//return 'package_id';
        return 'id';    // because db has set clustered index
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(
        	
        );
    }
    
    public function rules()
    {
        return array(      	
        	array('id, name, type', 'safe'),
        );
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
        	        	    
        );
    }
    
	public static function unitsListArray($type, $select_msg=true)
    {
    	if(!array_key_exists($type, self::$types))
    		return array();    		
        
        $db = Yii::app()->db;
        
        $units = $db->createCommand()
                ->select('id, name')
                ->from('tbl_dictionary')
                ->where('type=:type', 
                        array(
                            ':type'=>self::$types[$type])
                        )                
                ->queryAll();
        
        // 'empty' option
        if($select_msg)
        	$units = array_merge(
                array(array('id'=>0, 'name'=>Yii::t('common', 'select'))),
                $units
            );
                       
        return $units;        
    }
    
    
}
