<?php

 
class ReCaptchaAPI {
    
	public static function checkRecaptchaResponse($response)
    {
        $ch = curl_init();
        // Set URL on which you want to post the Form and/or data
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        // Data+Files to be posted
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('response'=>$response, 'secret'=> Yii::app()->params['recaptcha']['secret']));
        // Pass TRUE or 1 if you want to wait for and catch the response against the request made
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // For Debug mode; shows up any error encountered during the operation
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        // Execute the request
        $response = curl_exec($ch);
//        var_dump($response);
        $responseData = json_decode($response, true);
//        var_dump($responseData);
//        echo '<br>$responseData success: '.$responseData['success'];
        if(!$responseData || !isset($responseData['success']) || !$responseData['success'])
//            echo '<br>bledna walidacja';
            return false;
        else
            return true;
//            echo '<br>poprawna walidacja';
    }
}
?>