<?php
/**
 * Połączenie z bazą danych.
 * 
 * Dodana weryfikacja domeny.
 * 
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */

Yii::import('ext.rdb.RDbConnection');
Yii::import('ext.rdb.RDbCommand');
        
class DbConnection extends RDbConnection 
{
    /**
     * Domena lub tablica domen, dla których jest przypisana baza.
     * @var string|array
     */
    public $forDomain = null;
    
    
    public $profileTimeLimit = 1.0;

    
    /**
     * Otwiera połączenie z bazą.
     * Dodana weryfikacja domeny ($forDomain).
     */
    protected function open() {		
        if (is_string($this->forDomain)) {
            $this->forDomain = array($this->forDomain);
        }
        
        // cały mechanizm nie ma sensu z tą linią kodu, ale wprowadzamy subdomeny, a wymaga obsługi wildcard *.
        $this->forDomain[] = Yii::app()->request->serverName;        
        
        if (in_array(Yii::app()->request->serverName, $this->forDomain) == false) {
            $message = Yii::t('messages', 'The database is not connected propertly! Please check config files.');
            // yii message will not work because it cannot be store in session through CDbSession
            //Yii::app()->user->setFlash('error', $message);
            echo $message;
            Yii::app()->end();
        }
        parent::open();
    }

}
