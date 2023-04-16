<?php
/**
 * Konfiguracja rozszerzenia EAuth.
 *
 * @category config
 * @package 
 * @author 
 * @copyright (C) 2015
 */

return array(
            'class' => 'ext.eauth.EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache'.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'services' => array( // You can change the providers and their classes.
                /*'google' => array(
                    'class' => 'GoogleOpenIDService',
                        //'realm' => '*.example.org',
                    ),
                    'yandex' => array(
                        'class' => 'YandexOpenIDService',
                        //'realm' => '*.example.org',
                    ),
                    'steam' => array(
                        'class' => 'SteamOpenIDService',
                        //'realm' => '*.example.org',
                    ),
                    'yahoo' => array(
                        'class' => 'YahooOpenIDService',
                        //'realm' => '*.example.org',
                        ),
                        'wargaming' => array(
                            'class' => 'WargamingOpenIDService'
                        ),
                        'twitter' => array(
                            // register your app here: https://dev.twitter.com/apps/new
                            'class' => 'TwitterOAuthService',
                            'key' => '...',
                            'secret' => '...',
                        ),*/
                        'google_oauth' => array(
                            // register your app here: https://code.google.com/apis/console/
                            'class' => 'application.components.eauth.CustomGoogleService',
                            //'class' => 'GoogleOAuthService',
                            
                            /*'client_id' => '620805939068-8lc7kc2r9rlsqljh899si76h6pkt6mnb.apps.googleusercontent.com',
                            'client_secret' => '1vksUetPoqTJucqZGk_2QvUP',*/

                            //produkcja
                            'client_id' => '660373387922-8gs0rkk59sevkv2od6afcd08m6t6q2di.apps.googleusercontent.com',
                            'client_secret' => 'slBi8xgS02OIFzN9sSDEHxkl',

                            'title' => 'Google (OAuth)',
                            //'scope' => 'https://www.googleapis.com/auth/userinfo.email'
                            'scope' => 'https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email'
                        ),
                        /*'yandex_oauth' => array(
                            // register your app here: https://oauth.yandex.ru/client/my
                            'class' => 'YandexOAuthService',
                            'client_id' => '...',
                            'client_secret' => '...',
                            'title' => 'Yandex (OAuth)',
                        ),*/
                        'facebook' => array(
                            // register your app here: https://developers.facebook.com/apps/
                            'class' => 'application.components.eauth.CustomFacebookService',
                            //'class' => 'FacebookOAuthService',
                            //'client_id' => '1417438161894315',
                            //'client_secret' => '0f685802b9773096ded197ee117f84e0',
                            //produkcja
                            //firmbook.eu
//                            'client_id' => '828715190515724',
//                            'client_secret' => '94fc6dea6fa26dea80273f401b7c4eba',
                            // nowe wymaga weryfikacji przez fb
                            // 20210516
                            'client_id' => '763521294039645',
                            'client_secret' => 'bc14224879faddb45a1241ff622c7fc2',
                            'scope' => 'public_profile,email'
                        	//firmbook.pl
                            /*'client_id' => '1609914385927764',
                            'client_secret' => '01e6a4c7e7f567c43db8e6fcdfc42f7e',
                            'scope' => 'public_profile,email'*/	
                        ),
                        /*'linkedin' => array(
                            // register your app here: https://www.linkedin.com/secure/developer
                            'class' => 'LinkedinOAuthService',
                            'key' => '...',
                            'secret' => '...',
                        ),
                        'github' => array(
                            // register your app here: https://github.com/settings/applications
                            'class' => 'GitHubOAuthService',
                            'client_id' => '...',
                            'client_secret' => '...',
                        ),
                        'live' => array(
                            // register your app here: https://manage.dev.live.com/Applications/Index
                            'class' => 'LiveOAuthService',
                            'client_id' => '...',
                            'client_secret' => '...',
                        ),
                        'vkontakte' => array(
                            // register your app here: https://vk.com/editapp?act=create&site=1
                            'class' => 'VKontakteOAuthService',
                            'client_id' => '...',
                            'client_secret' => '...',
                        ),
                        'mailru' => array(
                            // register your app here: http://api.mail.ru/sites/my/add
                            'class' => 'MailruOAuthService',
                            'client_id' => '...',
                            'client_secret' => '...',
                        ),
                        'moikrug' => array(
                            // register your app here: https://oauth.yandex.ru/client/my
                            'class' => 'MoikrugOAuthService',
                            'client_id' => '...',
                            'client_secret' => '...',
                        ),
                        'odnoklassniki' => array(
                            // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
                            // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
                            'class' => 'OdnoklassnikiOAuthService',
                            'client_id' => '...',
                            'client_public' => '...',
                            'client_secret' => '...',
                            'title' => 'Odnokl.',
                        ),
                        'dropbox' => array(
                            // register your app here: https://www.dropbox.com/developers/apps/create
                            'class' => 'DropboxOAuthService',
                            'client_id' => '...',
                            'client_secret' => '...',
                        ),
                        'eve' => array(
                            // register your app here: https://developers.eveonline.com/applications
                            'class' => 'EveOnlineOAuthService',
                            'client_id' => '...',
                            'client_secret' => '...',
                        ),
                        'slack' => array(
                            // register your app here: https://api.slack.com/applications/new
                            'class' => 'SlackOAuthService',
                            'client_id' => '...',
                            'client_secret' => '...',
                            'title' => 'Slack',
                        ),*/
    		
                    ),
    		);
    	/* social login */
        