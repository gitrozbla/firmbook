<?php

class Country extends ActiveRecord
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
        return '{{country}}';
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
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    /*public function relations()
    {
        return array(
        	
        );
    }*/
    
    /*public function rules()
    {
        return array(      	
        	array('name', 'safe'),
        );
    }*/
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    /*public function attributeLabels() {
        return array(
        	        	    
        );
    }*/
    
    // państwa w wybranym języku, np. do wyboru w fromularzu firmy
    public static function getCountries()
    {
    	$language = Yii::app()->language;
    	
    	if ($language != Yii::app()->sourceLanguage) {
    		$query = 'SELECT c.id, c.code, c.name as text, m.translation as translation'
      			.' FROM tbl_country c LEFT JOIN tbl_source_message s on s.message=c.name'
      			.' LEFT JOIN tbl_message m on m.id=s.id'
      			.' WHERE s.category=\'country.name\' AND m.language=:language'      					
      			.' ORDER BY translation COLLATE utf8_general_ci';				
    		
    		$command = Yii::app()->db->createCommand($query);
    		$command->bindParam(':language', $language);
    		$countries = $command->queryAll();
    		
    		/*$countries = Yii::app()->db->createCommand()
    			->select('c.id, c.code,, c.name as text, '
                            . 'm.translation as translation')
            	->from('tbl_country c')                
            	->leftJoin('tbl_source_message s', 's.message=c.name')                
                ->leftJoin('tbl_message m', 'm.id=s.id')
                ->where('s.category=\'country.name\''
                	. ' AND m.language=:language', 
                    array(                                
                    	':language' => $language,
                    ))
                ->order('translation COLLATE utf8_polish_ci')                    
                ->queryAll();*/
    		
    	} else {
    		$countries = Yii::app()->db->createCommand()
    			->select('c.id, c.code as code, c.name as text, '
    				. 'c.name as translation')
    			->from('tbl_country c')				
    			/*->where('s.category=\'country.name\''
    				. ' AND m.language=:language',
    				array(
    					':language' => $language,
    				))*/
    			->order('name')
    			->queryAll();
    	}
    	
    	foreach($countries as $key=>$value) {    		
    		if ($value['translation']) {
    			$countries[$key]['text'] = $value['translation'];
    		}
    		unset($countries[$key]['translation']);
    	}
    	
    	return $countries;
    	
    }
	/*public static function unitsListArray($type)
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
        $units = array_merge(
                array(array('id'=>0, 'name'=>Yii::t('common', 'select'))),
                $units
                );
                       
        return $units;        
    }*/
    
    
}
