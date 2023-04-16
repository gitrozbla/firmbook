<?php
/**
 * Bazowa klasa dla modeli.
 *
 * Uzupełnia funkcjonalności o generowanie ścieżki do posiadanych plików.
 *
 * @category  components
 * @package   components
 * @author    BAI
 * @copyright (C) 2014 BAI
 */
class ActiveRecord extends CActiveRecord 
{
    /**
     * Ścieżka do posiadanych przez obiekt plików.
     * @var string 
     */
    protected $filesPath = null;
    
    
    protected static $_tableSchemas=array();
    
    /**
     * Tworzy instancję.
     * @param string $className
     * @return object
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Generuje ścieżkę do posiadanych plików.
     * @return string
     */
    public function getFilesPath () {
        if ($this->filesPath === null) {
            $id = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $this->primaryKey);
            $hash = Yii::app()->params['key']['systemSalt'];
            $this->filesPath = $id.'_'.$hash;
        }
        
        return $this->filesPath;
    }
    
    /**
     * Wsparcie dla MySQL clustered index 
     * (pozwala przedefiniować klucz główny).
     * @return CDbTableSchema opis tabeli.
     */
	public function getTableSchema()
    {
        //return $table = parent::getTableSchema();
        $class = get_class($this);        
        if (!isset(self::$_tableSchemas[$class])) {
            $table = parent::getTableSchema();            
            $pk = $this->primaryKey();                	
            if ($pk) {
                $oldPrimaryKey = $table->primaryKey;
                if (is_array($oldPrimaryKey)) {
                    foreach($oldPrimaryKey as $name) {
                        if(isset($table->columns[$name])) {
                            $table->columns[$name]->isPrimaryKey = false;
                        }
                    }
                } else if ($oldPrimaryKey !== null) {
                    $table->columns[$oldPrimaryKey]->isPrimaryKey = false;
                }
                $table->primaryKey = $pk;
                if (is_array($pk)) {
                    foreach($pk as $name) {
                    	if(isset($table->columns[$name])) {
                            $table->columns[$name]->isPrimaryKey = true;
                        }                   	
                    }
                } else {    
	                if(isset($table->columns[$pk])) {
	                    $table->columns[$pk]->isPrimaryKey = true;
	                }
                }
            }
            self::$_tableSchemas[$class] = $table;            
        }        
        return self::$_tableSchemas[$class];
    }
    
    public function getTableSchema_old()
    {
        //return $table = parent::getTableSchema();
        $class = get_class($this);
        if (!isset(self::$_tableSchemas[$class])) {
            $table = parent::getTableSchema();
            $pk = $this->primaryKey();
            if ($pk) {
                $oldPrimaryKey = $table->primaryKey;
                if (is_array($oldPrimaryKey)) {
                    foreach($oldPrimaryKey as $name) {
                        if(isset($table->columns[$name])) {
                            $table->columns[$name]->isPrimaryKey = false;
                        }
                    }
                } else if ($oldPrimaryKey !== null) {
                    $table->columns[$oldPrimaryKey]->isPrimaryKey = false;
                }
                $table->primaryKey = $pk;
                if(isset($table->columns[$pk])) {
                    $table->columns[$pk]->isPrimaryKey = true;
                }
            }
            self::$_tableSchemas[$class] = $table;
        }
        return self::$_tableSchemas[$class];
    }
    
}
