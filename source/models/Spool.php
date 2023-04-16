<?php


class Spool extends ActiveRecord {
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }        
    
    public function tableName() {
        return '{{spool}}';
    }
    
    public function primaryKey()
    {    	
        return 'id';    // because db has set clustered index
    }
    
    
}
