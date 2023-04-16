<?php

class Message extends ActiveRecord
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
        return '{{message}}';
    }
    
/**
     * Nazwa kolumny głównej.
     * @return string
     */
	public function primaryKey()
    {
    	//return 'package_id';
        return array('id', 'language');    // because db has set clustered index
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
        	array('id, language, translation', 'safe'),
        );
    }
    
    /**
     * Nazwy atrybutów.
     * @return array
     */
    public function attributeLabels() {
        return array(
        	'language' => 'Język',
            'translation' => 'Tłumaczenie',        	    
        );
    }
    
    
    
}
