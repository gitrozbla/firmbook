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

// debugowanie - ZAWSZE WYLACZONE!
//defined('YII_DEBUG') or define('YII_DEBUG',true);
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
defined('YII_DEBUG') or define('YII_DEBUG',false);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 0);
// debugowanie poczty - aktywować tylko w czasie testów!
//$config['components']['mailer']['SMTPDebug'] = true;

// widzimy wszystkie błędy
// error_reporting(E_ALL);
error_reporting(0);


// wyłączamy cache, żeby zawsze odświeżały się zmiany
/*$config['components']['cache'] = array(
	'class'=>'CDummyCache'
);*/

// logi
/*$config['components']['log']['routes'][] = array(
	'class'=>'CWebLogRoute',
	'showInFireBug'=>true,
	'ignoreAjaxInFireBug'=>false,
	//'levels'=>'error, warning',
	//'enableProfiling'=>true,
	//'enableParamLogging'=>true,
);*/
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
$config['params']['compress'] = true;

// wyświetlanie w logach zapytań do bazy
$config['components']['db']['enableProfiling'] = false;
$config['components']['db']['profileTimeLimit'] = 0.5;

$config['components']['bootstrap']['minify'] = true;

$config['params']['creatorsUrl'] = 'https://fbcreators.eu';

return $config;
