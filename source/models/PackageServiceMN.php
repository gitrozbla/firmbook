<?php

class PackageServiceMN extends ActiveRecord
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
        return '{{package_service_mn}}';
    }
    
	/**
     * Nazwa kolumny głównej.
     * @return string
     */
	public function primaryKey()
    {
    	//return 'package_id';
        return array('package_id', 'service_id');    // because db has set clustered index
    }
    
    /**
     * Relacje bazodanowe.
     * @return array Konfiguracja relacji.
     */
    public function relations()
    {
        return array(            
        	'package' => array(self::BELONGS_TO, 'Package', 'package_id', 'together'=>true),
        	'service' => array(self::BELONGS_TO, 'PackageService', 'service_id', 'together'=>true)        
        );
    }
    
	
}
