<?php

/**
 * Konfiguracja główna całego systemu.
 *
 * Agreguje również konfigurację z poszczególnych plików.
 *
 * @category config
 * @package system\config
 * @author BAI
 * @copyright (C) 2014 BAI
 */

error_reporting(E_ERROR);

ini_set('max_execution_time', 30);

// Pliki z folderu user.
$userPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;


// parametry dodatkowe
// Wczytane wcześniej, bo niektóre będą potrzebne do konfiguracji komponentów
$sessionTimeout = 1800;
/** Języki systemu. */
$languageFile = require ($userPath . 'language.php');
$defaultLanguage = $languageFile['defaultLanguage'];
$sourceLanguage = 'en';
$languages = $languageFile['languages'];
$languagesParam = '<language:(' . $sourceLanguage;
foreach ($languages as $key => $value) {
    $languagesParam .= '|' . $key;
}
$languagesParam .= ')>/';

$branding = require($userPath . 'branding.php');
$keyConfig = require($userPath . 'key.php');
$mailConfig = require($userPath . 'mail.php');
$adminConfig = require($userPath . 'admin.php');
$hostInfo = require($userPath . 'host.php');
$db = require ($userPath . 'database.php');
$packagesConfig = require($userPath . 'packages.php');
$eauth = require($userPath . 'eauth.php');


////////////////////////
// tablica parametrów //
////////////////////////
return array(
        //id dodane w celu obsługi plików cookie dla subdomen
	'id' => 'firmbookeu',

    'name' => $branding['title'],

    'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'runtimePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'
        .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'runtime',
    'aliases' => array(
        'ext' => 'webroot.extensions',
    ),
    
    // potrzebne do działania niektórych rozszerzeń
    'preload' => array(
        'bootstrap', // bootstrap przed logami!
        'log',
        'rights',
        'stats',  // run statistics'
        'cron',
   
   	//'comments',
    ),
    // autoload - nie potrzeba załączać klas
    'import' => array(
        'application.models.*',
        'application.controllers.*',
        'application.components.*',
        'application.components.other.*',
        'application.components.validators.*',
        'application.components.widgets.*',
        'application.components.widgets.editable.*',
        'application.components.widgets.GridView.*',
        'ext.bootstrap.widgets.*',
        'zii.widgets.*',
        /* rights */
        'application.modules.rights.RightsModule',
        'application.modules.rights.components.*',
    	/* comments */
       // 'application.modules.comments.CommentsModule',
       
    	/* social login */
    	'ext.eoauth.*',
    	'ext.eoauth.lib.*',
    	'ext.lightopenid.*',
    	'ext.eauth.*',
    	'ext.eauth.services.*',
    	/* social login */
    ),

    'modules' => array(
        'rights' => array(
            'superuserName'=>'Superadmin',
            'install'=>false,
        ),
        'phpbb',
		/*'comments'=>array(
	        //you may override default config for all connecting models
	        'defaultModelConfig' => array(
	            //only registered users can post comments
	            'registeredOnly' => true,
	            //'registeredOnly' => false,
	            'useCaptcha' => false,
	            //allow comment tree
	            'allowSubcommenting' => true,
	            //display comments after moderation
	            'premoderate' => false,
	            //action for postig comment
	            'postCommentAction' => 'comments/comment/postComment',
	            //super user condition(display comment list in admin view and automoderate comments)
	            'isSuperuser'=>'true',
	            //'isSuperuser'=>'Yii::app()->user->checkAccess("moderate")',
	            //order direction for comments
	            'orderComments'=>'DESC',
	        ),
	        //the models for commenting
	        'commentableModels'=>array(
	            //model with individual settings
	            'Company'=>array(
	                'registeredOnly'=>true,
	                //'useCaptcha'=>true,
	                'allowSubcommenting'=>false,
	                //config for create link to view model page(page with comments)
	                'pageUrl'=>array(
	                    'route'=>'companies/show',
	                    'data'=>array('id'=>'item_id'),
	                ),
	            ),
	            //model with default settings
	            'ImpressionSet',
	        ),
	        //config for user models, which is used in application
	        'userConfig'=>array(
	        	'class'=>'phpbb.components.PhpBBWebUser',
	            //'class'=>'User',
	            'nameProperty'=>'username',
	            'emailProperty'=>'email',
	        )
        )*/
    	'likedislike'	
    ),
    
    // jezyk
    'language' => $defaultLanguage,
    'sourceLanguage' => $sourceLanguage,
    // podstawowe komponenty (Yii)
    'components' => array(
    	/* social login */
        'loid' => array(
            'class' => 'ext.lightopenid.loid',
        ),
    	/* social login */
    	'eauth' => $eauth,	    	
        'session' => array(
            'class' => 'DbHttpSession',
            //'class' => 'CDbHttpSession',
            'connectionID' => 'db',
            'timeout' => $sessionTimeout,
            'sessionTableName' => 'tbl_yii_session',
            'autoCreateSessionTable' => true,
            //'cookieMode' =>'only',
            //'cookieParams' => array('secure' => false, 'httponly' => false),
            //dodane w celu obsługi subdomen
			//'savePath' => '/some/writeable/path',
	        /*'cookieMode' => 'allow',
	        'cookieParams' => array(
	            'path' => '/',
    			//'domain' => '.firmbook.pl',
    			'domain' => '.firmbookeu.localhost',
	            //'domain' => '.yourdomain.com',
	            'httpOnly' => true,
	        ),*/
	        //
        ),
        'user' => array(
            // inheritance: PhpBBWebUser -> WebUser -> RWebUser -> CWebUser
            'class' => 'WebUser',//'phpbb.components.PhpBBWebUser',
            'loginUrl'=>array('account/login'),
            'allowAutoLogin' => true,   // login using cookies
            //'returnUrl' => dirname($_SERVER['PHP_SELF']),
        ),
        'db' => array_merge(
                array(
                    'class' => 'DbConnection',
                    'tablePrefix' => 'tbl_',
                    ), 
                $db
        ),
        'forumDb'=>array_merge(
                array(
                    'class' => 'DbConnection',
                    'tablePrefix' => 'phpbb_',
                    ), 
                $db
        ),
        'phpBB'=>array(
            'class'=>'phpbb.extensions.phpBB.phpBB',
            'path'=>'webroot._forum',
        ),   
        'authManager' => array(
            'class' => 'RDbAuthManager',
            'connectionID' => 'db',
            'defaultRoles'=>array('Guest'), // always assigned
            // force lower table names for linux hosts support
            'assignmentTable' => 'tbl_auth_assignment',
            'rightsTable' => 'tbl_rights',
            'itemChildTable' => 'tbl_auth_item_child',
            'itemTable' => 'tbl_auth_item',
        ),
        'errorHandler' => array(
            // kontroler siteControler, akcja errorAction do wyświetlania błędów
            'errorAction' => 'site/error',
            'discardOutput' => false, // żeby się gzip nie posypał
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                /* array(
                  // log na email)
                  'class'=>'CEmailLogRoute',
                  'levels'=>'error, warning',  // lub też trace, info
                  'emails'=>'wojciech.alaszewski@bai.pl',
                  //'categories'=>'system.*', // dla wybranych kategorii
                  ), */
                array(
                    // log do pliku
                    'class' => 'CFileLogRoute',
                ),
                /*'sessionLogRoute' => array(
                    // log zapamiętany w sesji (dla raportowania)
                    'class' => 'SessionLogRoute',
                    'maxErrors' => 20,
                    'maxWarnings' => 15,
                ),*/
            ),
        ),
        'messages' => array(
            'class' => 'MyMessageSource',
            /*'onMissingTranslation' => array(
                'LanguageEventHandler',
                'handleMissingTranslation',
            ),*/
        ),
        'dbMessages' => array(
            'class' => 'DbMessageSource',
            'sourceMessageTable' => 'tbl_source_message',
            'translatedMessageTable' => 'tbl_message',
            'cachingDuration' => (YII_DEBUG ? 0 : 60*60*24)
        ),
        'request' => array_merge(
            array(
		'class' => 'HttpRequest',
                //'class' => 'CHttpRequest',
                // HMAC - bezpieczniejsze cookie
                'enableCookieValidation' => true,
                // przeciw Cross-site Request Forgery
                'enableCsrfValidation' => true,
                //w celu obejścia sprawdzania tokena CSRF pod DOTPAY
                'noCsrfValidationRoutes'=>array(
                    '_packages/paymentconfirm',
                    '_pakiety/paymentconfirm',
                    '_packages/transactionconfirm',

                    '_pakiety/transactionconfirm',
                            //http://firmbookeu.localhost/likedislike/default/likedislike
                    '_likedislike/default/likedislike',
                    '_strona/zaloguj',
                    '_site/login',

                    '_konto/remote_login',
                    '_account/remote_login',

                    '_promotion/paymentconfirm',                		
                    '_promocja/paymentconfirm',                		
                    '_promotion/transactionconfirm',                		
                    '_promocja/transactionconfirm',
                		
                	//Creators
                	'packages/paymentconfirm',
                	'pakiety/paymentconfirm',
                	'packages/transactionconfirm',
                	'pakiety/transactionconfirm',
                	/*'_cron/index',	
                	'cron/index',*/
                ),
                'csrfCookie' => array(
                    'domain' => 'firmbook.eu',
                    //'domain' => 'firmbook.pl',
                    //'domain' => '.firmbook.pl',
                    //'domain' => '.firmbookeu.localhost',
                    //'domain' => 'firmbookeu.localhost',
                ),    
            ),
            /*array(
                'hostInfo' => 'http://firmbookeu.localhost',
            )*/
            $hostInfo
        ),
        'urlManager' => array(
            'class' => 'UrlManager',
            // nazwa hosta nie zawiera się w regułach
            // przyjazne linki
            'showScriptName' => false,
            'urlFormat' => 'path',
            // caseSensitive musi być true, żeby rights oraz giix działało =/
            // do tego Windows nie rozróżnia wielkości liter, Unix/Linux tak
            // trzymać się konwencji! folder:'nazwa_modulu', plik i klasa:'Nazwa_moduluModule'
            'caseSensitive' => true,
            // uproszczenia dla jeszcze bardziej przyjaznych linków
            'rules' => array(
                // pusty url
                //$languagesParam => '',
				//'http://<name:abc>.firmbookeu.localhost/' => array('company/show','matchValue'=>true),
        		//'http://<user:\w+>.example.com/<lang:\w+>/profile' => 'user/profile',
                //'http://<subdomain:\w+>.firmbookeu.localhost' => 'companies/show',
                
				//'http://<name:\w+>.firmbookeu.localhost' => 'companies/show',
        
                'page/<name:[\w\-]+>' => 'pages/show',
                'category/<name:[\w\-]+>' => 'categories/show',
                'company/<name:[\w\-]+>' => 'company/show',
                'product/<name:[\w\-]+>' => 'products/show',
                'service/<name:[\w\-]+>' => 'services/show',
               // 'company/<name:[\w\-]+>' => 'companies/show',
            ),
        ),
        'widgetFactory' => array(
            'class' => 'application.components.WidgetFactory',
            'widgets' => array(
                'ActiveForm' => array(
                    'enableAjaxValidation' => false,
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'validateOnChange' => true,
                        'validateOnType' => true,     
                    ),
                ),
                'EditableField' => array(
                    'mode' => 'inline',
                ),
                'QRCodeGenerator' => array(
                    'subfolderVar' => false,
                   	//niestety podana tutaj sciezka moze nie zadzialac,
                	//po wywolaniu Yii::app()->file->filesPath; w np. UserFile->generateUrl()
                	//gubi sciezki, dlatego sciezki ustawiane sa podczas wywolania w widoku
					'filePath' => 'assets/qr',                    
                    'fileUrl' => 'assets/qr',
                    'displayImage'=>true,
                    'errorCorrectionLevel'=>'L', // L,M,Q,H
                    'matrixPointSize'=>10, // max 10
                    'imageTagOptions'=> array('class'=>'qr'),
                ),
                'TbActiveForm' => array(
                    'enableClientValidation'=>true,
                    'stateful'=>true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                    'focus'=>'input:visible:enabled:first, '
                        . 'input.error:visible:enabled:first',
                ),
                'COutputCache'=>array(
                    'duration' => 3600*24,
                ),
            ),
        ),
        'bootstrap' => array(
            'class' => 'ext.yiibooster.components.Bootstrap',
            'fontAwesomeCss' => true,
            //'responsiveCss' => false,
        ),
        //X-editable config
        /*'editable' => array(
            'class'     => 'ext.x-editable-yii.EditableConfig',
            'form'      => 'bootstrap',        //form style: 'bootstrap', 'jqueryui', 'plain' 
            'mode'      => 'inline',            //mode: 'popup' or 'inline'  
        ),*/    
        'mailer' => array_merge(
            array(
                'class' => 'application.components.MyMailer',
                'SMTPDebug' => false,
                'FromNameDefault' => $branding['title'],
                'WordWrap' => 900, 
                'isHTML' => true,
                'Priority' => 3,    // normal
                'CharSet' => 'UTF-8',
                'Encoding' => '8bit',
                'ContentType' => 'text/html; charset=utf-8\r\n',
            ), 
            $mailConfig
        ),
        'file' => array(
            'class' => 'application.components.other.File',
            'filesPath' => 'files',
        ),
        'image'=>array(
            'class'=>'ext.image.CImageHandler',
        ),
        'clientScript' => array(
            'class' => 'ClientScript',
            'defaultScriptFilePosition' => CClientScript::POS_END,
            'packages'=>array(
                'jquery'=>array(
                    'baseUrl'=>'js/vendor/jquery',
                    //'js'=>array('jquery-1.10.2.min.js'),   // custom version
                    //'js'=>array('jquery-2.0.3.min.js'),   // custom version
                    'js'=>array('jquery-2.1.0.min.js'),   // custom version
                ),
            ),
        ),
        'cache' => array(
            //'class'=>'CDummyCache',
            'class' => 'CDbCache',
            'connectionID' => 'db',
            'cacheTableName' => 'tbl_yii_cache',
            'autoCreateCacheTable' => true,
        ),
        'stats' => array(
            'class'=>'StatsCollector',  // run statistics
        ),
        'cron' => array(
            'class'=>'CronService',
        ),
    ),
    // m.in. kompresja gzip
    'onBeginRequest' => array('BeginEndEventHandler', 'beginRequest'),
    'onEndRequest' => array('BeginEndEventHandler', 'endRequest'),

    // dodatkowe parametry, dostępne pod  Yii::app()->params['paramName']
    'params' => array(
    	'packages' => $packagesConfig,
    
        // strona w ajax
        // czy włączona kompresja html/css/js
        'compress' => true,
        // koniec sesji po
        'sessionTimeout' => $sessionTimeout,
        // języki
        'languages' => $languages,
        'defaultLanguage' => $defaultLanguage,
        // Konfiguracja indywidualna
        'branding' => $branding,
        // Klucze systemu
        'key' => $keyConfig,
        // copyright kodu
        'copyrightInfo' => '&copy;BAI',
        'admin' => $adminConfig,
        'rememberTime' => 3600 * 24 * 30, // 30 days
		
        /*'facebook' => array(
    		//'facebookId' => '1417438161894315', //firmbookeu.localhost    		
        	//'facebookId' => '370234239844080', //www.firmbook.pl
        	'facebookId' => '845388612181715', //www.firmbook.pl , konto firmbook
        	//'facebookId' => '828715190515724', //www.firmbook.eu	
        		
    	),*/
      	'google' => array(
      		'map' => array(
      				'plLat' => 51.919438,
      				'plLng' => 19.145136,
      				'plZoom' => 6,
      				'locationZoom' => 13,
      				'markers' => array('A','B','C','D','E',
      								'F','G','H','I','J',
      								'K','L','M','N','O',
      								'P','Q','R','S','T',
      								'U','W','X','Y','Z')
      		),
      		//www.firmbook.eu
      		//'clientId' => '660373387922-8gs0rkk59sevkv2od6afcd08m6t6q2di.apps.googleusercontent.com',
      		//'clientSecret' => 'slBi8xgS02OIFzN9sSDEHxkl'	
      		// firmbook.pl	
      		//'clientId' => '620805939068-8lasopi0k0kllod9kinkadh444hmcmv3.apps.googleusercontent.com',
      		//'clientSecret' => '1vksUetPoqTJucqZGk_2QvUP'      					
      	),
    	'payments' => array(
    		'1' => array(
				'name' => 'MASTERCARD',
				'image' => 'MASTERCARD.png',
				'url' => array(
                    'en' => 'http://www.mastercard.com',
                    'pl' => 'http://mastercard.pl'
                )
			),
    		'2' => array(
				'name' => 'MAESTRO',
				'image' => 'MAESTRO.png',
				'url' => array(
                    'en' => 'http://www.maestrocard.com',
                    'pl' => 'http://www.maestrocard.com/pl/'
                )
			),
    		/*'3' => array(
				'name' => 'AMERICAN_EXPRESS',
				'image' => 'AMERICAN_EXPRESS.png',
				'url' => array(
                    'en' => 'https://www.americanexpress.com',
                    'pl' => 'https://www.americanexpress.com/poland/homepage.shtml'
                )
			),*/
    		'4' => array(
				'name' => 'PAY_PAL',
				'image' => 'PAY_PAL.png',
				'url' => array(
                    'en' => 'https://www.paypal.com/pl/webapps/mpp/home',
                    'pl' => 'https://www.paypal.com/pl/home'
                )
			),
    		'5' => array(
				'name' => 'VISA_ELECTRON',
				'image' => 'VISA_ELECTRON.png',
				'url' => array(
                    'en' => 'http://www.visa.com/globalgateway/gg_selectcountry.jsp',
                    'pl' => 'https://www.visa.pl/'
                )
			),
    		'6' => array(
				'name' => 'VISA',
				'image' => 'VISA.png',
				'url' => array(
                    'en' => 'http://www.visa.com/globalgateway/gg_selectcountry.jsp',
                    'pl' => 'https://www.visa.pl/'
                )
			),
    		'7' => array(
				'name' => 'WESTERN_UNION',
				'image' => 'WESTERN_UNION.png',
				'url' => array(
					'en' => 'https://www.westernunion.com/us/en/home.html?method=load&countryCode=US&languageCode=en&pagename=HomePage',
					'pl' => 'http://www.westernunion.pl/WUCOMWEB/staticMid.do?method=load&countryCode=PL&languageCode=pl&pagename=HomePage'
				)
            ),
            /*'8' => array(
				'name' => 'Cash'
			),*/     // REMOVED. USUNIĘTE, NIE UŻYWAĆ TEGO ID!
            '9' => array(
				'name' => 'BARCLAYCARD',
				'image' => 'BARCLAYCARD.png',
				'url' => array(
                    'en' => 'https://www.barclaycard.co.uk/personal',
                    'pl' => 'https://www.barclaycard.co.uk/personal'
                )
			),
            '10' => array(
				'name' => 'DOTPAY',
				'image' => 'DOTPAY.png',
				'url' => array(
                    'en' => 'http://www.dotpay.pl/en/',
                    'pl' => 'http://www.dotpay.pl/'
                )
			),
            '11' => array(
				'name' => 'FIRST DATA',
				'image' => 'FIRST_DATA.png',
				'url' => array(
                    'en' => 'https://www.firstdata.com/en_gb/home.html',
                    'pl' => 'https://www.firstdata.com/pl_pl/home.html'
                )
			),
            '12' => array(
				'name' => 'TPAY',
				'image' => 'TPAY.png',
				'url' => array(
                    'en' => 'https://tpay.com/en',
                    'pl' => 'https://tpay.com/'
                )
			),
            '13' => array(
				'name' => 'PAYU',
				'image' => 'PAYU.png',
				'url' => array(
                    'en' => 'http://payu.com/',
                    'pl' => 'https://www.payu.pl/'
                )
			),
            '14' => array(
				'name' => 'PRZELEWY24',
				'image' => 'PRZELEWY24.png',
				'url' => array(
                    'en' => 'https://www.przelewy24.pl/eng/',
                    'pl' => 'https://www.przelewy24.pl/'
                )
			),
            '15' => array(
				'name' => 'BITCOIN',
				'image' => 'BITCOIN.png',
				'url' => array(
                    'en' => 'https://bitcoin.org/en/',
                    'pl' => 'https://bitcoin.org/pl/'
                )
			)
		),
    	'delivery' => array(
    		'1' => array(
    			'name' => 'DHL',
                'image' => 'DHL.png',
				'url' => array(
					'en' => 'http://www.dhl.com/en.html',
					'pl' => 'http://www.dhl.com.pl/pl.html'
				)
			),
			'2' => array(
				'name' => 'UPS',
                'image' => 'UPS.png',
				'url' => array(
					'en' => 'http://www.ups.com/',
					'pl' => 'http://www.ups.com/?Site=Corporate&cookie=pl_pl_home&inputImgTag&setCookie=yes'
				)
			),
    		'3' => array(
				'name' => 'Fedex',
                'image' => 'FEDEX.png',
				'url' => array(
					'en' => 'http://www.fedex.com/',
					'pl' => 'http://www.fedex.com/pl/'
				)
			),
            //'4' REMOVED. USUNIĘTE, NIE UŻYWAĆ TEGO ID!
            '5' => array(
                'name' => 'DPD',
                'image' => 'DPD.png',
				'url' => array(
					'en' => 'https://www.dpd.com/',
					'pl' => 'https://www.dpd.com.pl/'
				)
            ),
            '6' => array(
                'name' => 'K-EX',
                'image' => 'K-EX.png',
				'url' => array(
					'en' => 'http://k-ex.pl/',
					'pl' => 'http://k-ex.pl/'
				)
            ),
            '7' => array(
                'name' => 'GLS',
                'image' => 'GLS.png',
				'url' => array(
					'en' => 'https://gls-group.eu/EU/en/gls-partner-united-kingdom',
					'pl' => 'https://gls-group.eu/PL/pl/grupa-gls'
				)
            ),
            '8' => array(
                'name' => 'TNT',
                'image' => 'TNT.png',
				'url' => array(
					'en' => 'https://www.tnt.com/express/en_us/site/home.html',
					'pl' => 'https://www.tnt.com/express/pl_pl/site/home.html'
				)
            )
    	),
    	'colors' => array(
    		'228c06', // zielony
    		'8c0655', // fiolet
    		'008ad2', // niebieski	
    	),    		
    	//'categoryLevel' => 2	
    ),

);
