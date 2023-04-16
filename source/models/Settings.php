<?php
/**
 * Model obiektu ustawień.
 * 
 * Baza zawiera jeden wiersz, a kolumny definiują parametry ustawień.
 * 
 * @category models
 * @package settings
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Settings extends ActiveRecord
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
        return '{{settings}}';
    }
    
    
}
