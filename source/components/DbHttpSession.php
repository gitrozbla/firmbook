<?php
/**
 * Obsługa sesji z pomocą bazy dnaych.
 * w celu dodania user_id w tabeli sesji
 * 
 * 
 * @category components
 * @package components
 * @author
 * @copyright (C) 2015
 */


        
class DbHttpSession extends CDbHttpSession 
{
    public $autoCreateSessionTable=false;
    //public $forDomain = null;    
    
	public function setUserId($userId)
    {
        $db=$this->getDbConnection();
        $db->setActive(true);
        $db->createCommand()->update(
            $this->sessionTableName,
             array('user_id'=>$userId),
           // array('userId'=>$userId), // I asume you added a column 'userId' to your session table
            'id=:id',
            array(':id'=>session_id())
       );
    }
    
    

}
