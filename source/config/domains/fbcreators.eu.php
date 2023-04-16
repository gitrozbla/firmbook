<?php
/**
 * Parametry dla firmbook creators.
 *
 * @category config
 * @package system\config\domains
 */
/** Wczytany oryginalny config. */
if (!isset($config)) {
    $config = require(dirname(__FILE__).'/../main.php');
}

$config['import'][] = 'application.modules.creators.CreatorsModule';

$config['id'] = 'creators';
$config['params']['branding'] = require(dirname(__FILE__).'/../user/branding-creators.php');
$config['name'] = $config['params']['branding']['title'];
$config['components']['mailer']['FromNameDefault'] = $config['params']['branding']['title'];

$config['params']['websiteMode'] = 'creators';
$config['params']['firmbookUrl'] = 'https://www.firmbook.eu';
$config['params']['hostInfo'] = 'https://www.fbcreators.eu';
$config['modules'][] = 'creators';
$config['components']['bootstrap']['bootstrapCss'] = false; // własny styl
$config['components']['bootstrap']['responsiveCss'] = true;

$config['components']['user']['loginUrl'] = array('site/index', 'please_log_in'=>1);
$config['components']['errorHandler']['errorAction'] = 'creators/site/error';


$config['components']['request']['csrfCookie']['domain'] = 'fbcreators.eu';
$config['components']['request']['hostInfo'] = 'https://www.fbcreators.eu';

//$config['components']['eauth']['services']['facebook']['client_id'] = '1593886947529198';
//$config['components']['eauth']['services']['facebook']['client_secret'] = 'effe10c0fc19e24f87106048b40f7798';

//$config['components']['eauth']['services']['facebook']['client_id'] = '763521294039645';
//$config['components']['eauth']['services']['facebook']['client_secret'] = 'bc14224879faddb45a1241ff622c7fc2';

// dziala tylko na koncie developerskim, dla reszty trzeba zglosic strone do weryfikacji w konsoli fb
$config['components']['eauth']['services']['facebook']['client_id'] = '469666103812029';
$config['components']['eauth']['services']['facebook']['client_secret'] = '88d8128be5e51f91a1147ea2ecc6ac18';

$config['components']['eauth']['services'] = array(
    'google_oauth' => array(        
        'class' => 'application.components.eauth.CustomGoogleService',        
        'client_id' => '660373387922-8gs0rkk59sevkv2od6afcd08m6t6q2di.apps.googleusercontent.com',
        'client_secret' => 'slBi8xgS02OIFzN9sSDEHxkl',
        'title' => 'Google (OAuth)',        
        'scope' => 'https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email'
    )
);


//$config['params']['calendarUrl'] = 'https://calendar.google.com/calendar/embed?src=m37kqgskjrlsahh0rc19o9615s%40group.calendar.google.com&ctz=Europe/Warsaw';
$config['params']['calendarUrl'] = 'https://calendar.google.com/calendar/embed?src=ito2siaai22fggebpcntvsiom0%40group.calendar.google.com&ctz=Europe/Warsaw';

// debugowanie
//defined('YII_DEBUG') or define('YII_DEBUG',true);
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
defined('YII_DEBUG') or define('YII_DEBUG',false);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 0);

// widzimy wszystkie błędy
//error_reporting(E_ALL);
error_reporting(0);

// logi
/*$config['components']['log']['routes'][] = array(
		'class'=>'CWebLogRoute',
		'showInFireBug'=>true,
		'ignoreAjaxInFireBug'=>false,
		//'levels'=>'error, warning',
		//'enableProfiling'=>true,
		//'enableParamLogging'=>true,
);*/

return $config;
