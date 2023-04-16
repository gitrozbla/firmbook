<?php
class CustomFacebookService extends FacebookOAuthService {
	/**
	 * https://developers.facebook.com/docs/authentication/permissions/
	 */
	protected $scope = 'email,user_birthday,user_hometown,user_location';

	/**
	 * http://developers.facebook.com/docs/reference/api/user/
	 *
	 * @see FacebookOAuthService::fetchAttributes()
	 */
	protected function fetchAttributes() {
		echo "<br>extensions/eauth/custom_service/CustomFacebookService->fetchAttributes()<br>";
		exit;
		$this->attributes = (array)$this->makeSignedRequest('https://graph.facebook.com/me');
	}
}
