<?php
/**
 * Parametry dodatkowe, tylko dla localhost.
 *
 * @category config
 * @package system\config\domains
 * @author BAI
 * @copyright (C) 2014 BAI
 */

/** Wczytany oryginalny config. */
$config = require(dirname(__FILE__).'/../main.php');

// nadpisujemy...

// debugowanie
defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
// debugowanie poczty - aktywować tylko w czasie testów!
//$config['components']['mailer']['SMTPDebug'] = true;

// widzimy wszystkie błędy
error_reporting(E_ALL);


// wyłączamy cache, żeby zawsze odświeżały się zmiany
$config['components']['cache'] = array(
	'class'=>'CDummyCache'
);

// logi
$config['components']['log']['routes'][] = array(
	'class'=>'CWebLogRoute',
	'showInFireBug'=>true,
	'ignoreAjaxInFireBug'=>false,
	//'levels'=>'error, warning',
	//'enableProfiling'=>true,
	//'enableParamLogging'=>true,
);
/*$config['components']['log']['routes'][] = array(
	// toolbar plugin
	'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
	'ipFilters'=>array('127.0.0.1'),
	//'levels'=>'error, warning, info',
);*/
/*$config['components']['log']['routes'][] = array(
	'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
	'enabled'=>defined('YII_DEBUG'),
);*/
/*$config['components']['log']['routes'][] = array(
	'class'=>'CProfileLogRoute',
	'enabled'=>defined('YII_DEBUG'),
);*/
/*
$config['components']['mailer']['SMTPDebug'] = 2;
*/

// wyłączamy kompresję generowanych js i css
$config['params']['compress'] = false;

// wyświetlanie w logach zapytań do bazy
$config['components']['db']['enableProfiling'] = true;
$config['components']['db']['profileTimeLimit'] = 0.5;

$config['components']['bootstrap']['minify'] = false;

//klucze zewnętrzne
//echo 'localhost.php';
//$config['params']['facebook']['facebookId'] = '1417438161894315';
//$config['params']['google']['clientId'] = '620805939068-8lasopi0k0kllod9kinkadh444hmcmv3.apps.googleusercontent.com';

$config['components']['request']['csrfCookie']['domain'] = 'firmbook.localhost';
//$config['components']['request']['hostInfo'] = 'http://firmbook.localhost';
$config['components']['request']['hostInfo'] = 'https://firmbook.localhost';

$config['params']['packages']['dotpayId'] = '729944';
$config['params']['packages']['dotpayPin'] = 'm2z36S2O9SPke3dsHuRShJnlNxz9GxUK';
$config['params']['packages']['dotpayPaymentUrl'] = 'https://ssl.dotpay.pl/test_payment/';

$config['params']['creatorsUrl'] = 'https://creators.localhost';

$config['components']['eauth']['services']['facebook']['client_id'] = '1417438161894315';
$config['components']['eauth']['services']['facebook']['client_secret'] = '0f685802b9773096ded197ee117f84e0';

$config['params']['productionMode'] = false;

$config['components']['mailer']['Username'] = 'wwwdev@firmbook.eu';
$config['components']['mailer']['Password'] = 'F44Mbookeu';
$config['components']['mailer']['FromDefault'] = 'wwwdev@firmbook.eu';

//uwaga na konfigurację bazy

return $config;
