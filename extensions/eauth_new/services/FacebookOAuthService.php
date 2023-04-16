<?php
/**
 * FacebookOAuthService class file.
 *
 * Register application: https://developers.facebook.com/apps/
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

require_once dirname(dirname(__FILE__)) . '/EOAuth2Service.php';

/**
 * Facebook provider class.
 *
 * @package application.extensions.eauth.services
 */
class FacebookOAuthService extends EOAuth2Service {

	protected $name = 'facebook';
	protected $title = 'Facebook';
	protected $type = 'OAuth';
	protected $jsArguments = array('popup' => array('width' => 585, 'height' => 290));

	protected $client_id = '';
	protected $client_secret = '';
	protected $scope = '';
	protected $providerOptions = array(
		'authorize' => 'https://www.facebook.com/dialog/oauth',
		'access_token' => 'https://graph.facebook.com/oauth/access_token',
	);

	protected function fetchAttributes() {
		echo "<br>extensions/eauth/services/FacebookOAuthService->fetchAttributes()<br>";
		exit;
		$info = (object)$this->makeSignedRequest('https://graph.facebook.com/me');

		$this->attributes['id'] = $info->id;
		$this->attributes['name'] = $info->name;
		$this->attributes['url'] = $info->link;
	}

	protected function getCodeUrl($redirect_uri) {
		if (strpos($redirect_uri, '?') !== false) {
			$url = explode('?', $redirect_uri);
			$url[1] = preg_replace('#[/]#', '%2F', $url[1]);
			$redirect_uri = implode('?', $url);
		}

		$this->setState('redirect_uri', $redirect_uri);

		$url = parent::getCodeUrl($redirect_uri);
		if (isset($_GET['js'])) {
			$url .= '&display=popup';
		}

		return $url;
	}

	protected function getTokenUrl($code) {
		return parent::getTokenUrl($code) . '&redirect_uri=' . urlencode($this->getState('redirect_uri'));
	}

	protected function getAccessToken($code) {
		echo '<br>extensions/eauth/services/FacebookOAuthService->getAccessToken()<br>';
		$response = $this->makeRequest($this->getTokenUrl($code), array(), false);
		echo '<br>$token getAccessToken:<br>';
		var_dump($response);
// 		parse_str($response, $result);		
// 		exit;
		$result =(array)json_decode($response);
		return $result;
	}

	/**
	 * Save access token to the session.
	 *
	 * @param array $token access token array.
	 */
	protected function saveAccessToken($token) {
		echo '<br>extensions/eauth/services/FacebookOAuthService->saveAccessToken()<br>';
		echo '<br>$token:<br>';
		echo $token['access_token'];
		echo '<br>$token:<br>';
		echo $token[0]['access_token'];
		echo '<br>$token dump:<br>';
		var_dump($token);
		$this->setState('auth_token', $token['access_token']);
		echo '<br>$this->access_token:<br>';
		var_dump($this->access_token);
		$this->setState('expires', isset($token['expires']) ? time() + (int)$token['expires'] - 60 : 0);
		$this->access_token = $token['access_token'];
		echo '<br>$this->access_token:<br>';
		var_dump($this->access_token);
	}

	/**
	 * Returns the error info from json.
	 *
	 * @param stdClass $json the json response.
	 * @return array the error array with 2 keys: code and message. Should be null if no errors.
	 */
	protected function fetchJsonError($json) {
		if (isset($json->error)) {
			return array(
				'code' => $json->error->code,
				'message' => $json->error->message,
			);
		}
		else {
			return null;
		}
	}
}
