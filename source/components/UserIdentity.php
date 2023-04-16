<?php
/**
 * Obiekt identyfikujący użytkownika.
 * 
 * Jest to specjalny obiekt wykorzystywany w procesie logowania.
 * Framework wymaga użycia go dla procesu logowania.
 * Implementuje funkcję identyfikującą.
 *
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class UserIdentity extends CUserIdentity 
{
    /**
     * ID użytkownika (id w tabeli).
     * @var int
     */
    protected $id;
    /**
     * Nazwa użytkownika.
     * @var string 
     */
    public $username;
    
    //public $package_id;
    
    public function getId() {
        return $this->id;
    }
    public function getUsername() {
        return $this->username;
    }
    
    /**
     * Weryfikacja użytkownika.
     * Potwierdza wiarygodność na podstawie loginu i hasła.
     * @return boolean Czy użytkownik poprawny.
     */
    public function authenticate($force=false) 
    {
        $user = User::model()->find('LOWER(username)=?', array(strtolower($this->username)));
        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (!$force && !$user->validatePassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->id = $user->id;
            $this->username = $user->username;
            $this->errorCode = self::ERROR_NONE;
            
            //$this->package_id = $user->package_id;
            $this->setState('package_id', $user->package_id);
            $this->setState('creators_package_id', $user->creators_package_id);
        }
        return $this->errorCode == self::ERROR_NONE;
    }

}
