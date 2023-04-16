<?php

class SourceMessage extends ActiveRecord
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
        return '{{source_message}}';
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(        	
        	'article' => array(self::BELONGS_TO, 'Article', 'object_id'),
        	'translations' => array(self::HAS_MANY, 'Message', 'id'),            
        );
    }
    
    public function rules()
    {
        return array(
        	array('id, category, object_id, message', 'safe'),
        );
    }	
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
        	'category' => 'Kategoria',
            'object_id' => 'Objekt',
        	'message' => 'Tekst',        	    
        );
    }
    
    
}
