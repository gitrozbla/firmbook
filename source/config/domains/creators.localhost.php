<?php
/**
 * Parametry dla firmbook creators.
 *
 * @category config
 * @package system\config\domains
 */
$config = array_merge( 
    require (dirname(__FILE__).'/localhost.php'),
	require (dirname(__FILE__).'/fbcreators.eu.php')
    //require (dirname(__FILE__).'/creators.firmbook.pl.php')
);

$config['components']['request']['csrfCookie']['domain'] = 'creators.localhost';
$config['components']['request']['hostInfo'] = 'https://creators.localhost';

$config['params']['firmbookUrl'] = 'https://firmbook.localhost';
$config['params']['productionMode'] = false;
$config['params']['hostInfo'] = 'https://www.fbcreators.eu';

return $config;