<?php
/**
 * Manager autoryzacji.
 *
 * Dodane listowanie osób uprawnionych.
 * 
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class DbAuthManager extends RDbAuthManager
{
	/**
	 * Wylistowanie użytkowników posiadających rolę.
	 * @param string|array $role Rola lub tablica ról.
	 * @param boolean $excludeSuperusers Czy pominąć użytkowników typu superuser.
	 * return array Tablica użytkowników (ID=>username).
	 */
	public function getUsersWithRole($role, $excludeSuperusers=false, $idOnly=false)
	{
		$users = Yii::app()->db->createCommand()
			->select('id'.(!$idOnly ? ', username' : ''))
			->from('tbl_user')
			->queryAll();
		
		$authManager = Yii::app()->authManager;
		
		$usersWithRole = array();
		
		
		foreach($users as $user) {
			if (is_array($role)) {
				foreach($roles as $value) {
					if (!$this->checkAccess($value, $user['id'])) {
						continue;
					}
				}
			} else {
				if (!$this->checkAccess($role, $user['id'])) {
					continue;
				}
			}
			if ($excludeSuperusers and $this->checkAccess('superuser', $user['id'])) {
				continue;
			}
			
			if ($idOnly) {
				$usersWithRole[] = $user['id'];
			} else {
				$usersWithRole[$user['id']] = $user['username'];
			}
			
		}
		
		return $usersWithRole;
	}
	
}
