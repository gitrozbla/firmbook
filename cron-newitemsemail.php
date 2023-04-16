<?php
if (php_sapi_name() != 'cli' ) {
    echo 'This must be run from command line';
    exit();
}

$_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'] = '/';
//$_SERVER['SERVER_NAME'] = 'firmbook.localhost';
$_SERVER['SERVER_NAME'] = 'www.firmbook.eu';
$_SERVER['argv'] = array(__FILE__, 'newitemsemail');

/**
 * Plik wejściowy do aplikacji.
 *
 * UWAGA! index.php musi być w UTH-8 bez BOM.
 * Z BOM są problemy typu 'headers already send'
 * http://mynthon.net/articles/php/utf-8
 *
 * @package system
 * @author BAI
 * @copyright (C) 2014 BAI
 */

/**
 * Funkcje dodatkowe, ogólnego użytku
 * Tutaj potrzebna funkcja endsWith(), żeby sprawdzić listę domen.
 * @see Func::endsWith()
 */
require(dirname(__FILE__).'/source/components/other/Func.php');

 /**
 * Yii
 * @link http://www.yiiframework.com/doc/guide/
 * Klasa Yii dziedziczy po oryginalnym YII z pliku 'framework/'
 * Umieszone poniżej konfiguracji, aby nie nadpisy można było ustawić YII_DEBUG
 */
require(dirname(__FILE__).'/source/components/Yii.php');

/** Konfiguracja ogólna. */
$domains = require(dirname(__FILE__).'/source/config/domains.php');
foreach ($domains as $key => $value) {
    if (Func::endsWith($_SERVER['SERVER_NAME'], $key)) {
        $config = require(dirname(__FILE__).'/source/config/domains/'.$value.'.php');
    }
}
if (isset($config) == false) $config = require(dirname(__FILE__).'/source/config/main.php');

// na koniec zakomentowac od linii ponizej do KONIEC
//define('YII_DEBUG',true);
//define('YII_TRACE_LEVEL', 3);
// debugowanie poczty - aktywować tylko w czasie testów!
//$config['components']['mailer']['SMTPDebug'] = true;

// widzimy wszystkie błędy
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//echo 'przed uruchomieniem';
// KONIEC
//file_put_contents(dirname(__FILE__) . '/last_cron_run.txt', date('r'));
// start
Yii::createConsoleApplication($config)->run();

file_put_contents(dirname(__FILE__) . '/last_cronnew_run.txt', date('r'));

?>
