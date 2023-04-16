<?php
class CustomFacebookService extends FacebookOAuthService {
	/**
	 * https://developers.facebook.com/docs/authentication/permissions/
	 */
// 	protected $scope = 'email,user_birthday,user_hometown,user_location';

	/**
	 * http://developers.facebook.com/docs/reference/api/user/
	 *
	 * @see FacebookOAuthService::fetchAttributes()
	 */
	
        protected function fetchAttributes() {		
		// z przykładu
// 		$this->attributes = (array)$this->makeSignedRequest('https://graph.facebook.com/v2.8/me');
		$info = (object)$this->makeSignedRequest('https://graph.facebook.com/me?fields=id,name,email', array(), false);
//                var_dump($info); exit;
		//$this->attributes['id'] = $info->id;
		$this->attributes['username'] = $info->id;
		//$this->attributes['name'] = $info->name;
		//$this->attributes['url'] = $info->link;
		
		$this->attributes['email'] = $info->email;
//		$this->attributes['forename'] = $info->first_name;
//		$this->attributes['surname'] = $info->last_name;
		
		$this->attributes['remote_source'] = User::REMOTE_SOURCE_FACEBOOK;
		$this->attributes['facebook_id'] = $info->id;
		
		$this->attributes['password'] =  User::$defaultPassword;
		$this->attributes['termsAccept'] =  true;
		
	}
    
	protected function fetchAttributes_org() {		
		// z przykładu
// 		$this->attributes = (array)$this->makeSignedRequest('https://graph.facebook.com/v2.8/me');
		$info = (object)$this->makeSignedRequest('https://graph.facebook.com/me?fields=id,name,email', array(), false);

		//$this->attributes['id'] = $info->id;
		$this->attributes['username'] = $info->id;
		//$this->attributes['name'] = $info->name;
		//$this->attributes['url'] = $info->link;
		
		$this->attributes['email'] = $info->email;
		$this->attributes['forename'] = $info->first_name;
		$this->attributes['surname'] = $info->last_name;
		
		$this->attributes['remote_source'] = User::REMOTE_SOURCE_FACEBOOK;
		$this->attributes['facebook_id'] = $info->id;
		
		$this->attributes['password'] =  User::$defaultPassword;
		$this->attributes['termsAccept'] =  true;
		
	}
	
	protected function getAccessToken($code) {
		$response = $this->makeRequest($this->getTokenUrl($code), array(), false);		
		// 		parse_str($response, $result);		
		$result =(array)json_decode($response);
		return $result;
	}
	
	protected function saveAccessToken($token) {
		$this->setState('auth_token', $token['access_token']);
		$this->setState('expires', isset($token['expires']) ? time() + (int)$token['expires'] - 60 : 0);
		$this->access_token = $token['access_token'];		
	}
	
	public function makeSignedRequest($url, $options = array(), $parseJson = true) {
		$result = parent::makeSignedRequest($url, $options, $parseJson);
		$result = (array)json_decode($result);
		return $result;
	}
}
