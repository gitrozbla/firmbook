<?php
class CustomGoogleService extends GoogleOAuthService {
	
	protected function fetchAttributes() {
		$info = (object)$this->makeSignedRequest('https://www.googleapis.com/oauth2/v1/userinfo', array(), false);
// 		$info = (object)$this->makeSignedRequest('https://www.googleapis.com/oauth2/v1/userinfo');
		//$info = (array)$this->makeSignedRequest('https://www.googleapis.com/oauth2/v1/userinfo');
				
		$this->attributes['username'] = $info->id;
		$this->attributes['email'] = $info->email;
		$this->attributes['forename'] = $info->family_name;
		
		$this->attributes['remote_source'] = User::REMOTE_SOURCE_GOOGLE;
		$this->attributes['google_id'] = $info->id;
		
		$this->attributes['password'] =  User::$defaultPassword;
		$this->attributes['termsAccept'] =  true;			
	}
	
	protected function getAccessToken($code) {
		$params = array(
				'client_id' => $this->client_id,
				'client_secret' => $this->client_secret,
				'grant_type' => 'authorization_code',
				'code' => $code,
				'redirect_uri' => $this->getState('redirect_uri'),
		);
		//return $this->makeRequest($this->getTokenUrl($code), array('data' => $params));
		$response = $this->makeRequest($this->getTokenUrl($code), array('data' => $params), false);
		// 		parse_str($response, $result);
		$result = (array)json_decode($response);		
		return $result;
	}
	
	protected function saveAccessToken($token) {
		$this->setState('auth_token', $token['access_token']);
		$this->setState('expires', isset($token['expires_in']) ? time() + (int)$token['expires_in'] - 60 : 0);
		$this->access_token = $token['access_token'];
	}
	
	public function makeSignedRequest($url, $options = array(), $parseJson = true) {
		$result = parent::makeSignedRequest($url, $options, $parseJson);
		$result = (array)json_decode($result);
		return $result;
	}
}
