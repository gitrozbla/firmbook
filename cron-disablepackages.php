<?php
if (php_sapi_name() != 'cli' ) {
    echo 'This must be run from command line';
    exit();
}

$_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'] = '/';
//$_SERVER['SERVER_NAME'] = 'firmbook.localhost';
$_SERVER['SERVER_NAME'] = 'www.firmbook.eu';
$_SERVER['argv'] = array(__FILE__, 'disablepackages');

/**
 * Plik wejściowy do aplikacji.
 *
 * UWAGA! index.php musi być w UTH-8 bez BOM.
 * Z BOM są problemy typu 'headers already send'
 * http://mynthon.net/articles/php/utf-8
 *
 * @package system 
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

Yii::createConsoleApplication($config)->run();

file_put_contents(dirname(__FILE__) . '/last_cronnew_run.txt', date('r'));

?>
