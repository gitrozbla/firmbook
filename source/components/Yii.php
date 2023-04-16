<?php

require(dirname(__FILE__).'/../../framework/YiiBase.php');

require(dirname(__FILE__).'/WebApplication.php');
require(dirname(__FILE__).'/ConsoleApplication.php');

/**
 * Klasa główna frameworku.
 * 
 * Zawiera statyczne metody, które mogą być wywoływane w każdej chwili.
 *
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Yii extends YiiBase
{
    /**
     * Tablica podmienianych aliasów.
     * @var type 
     */
    protected static $aliasesOfAliases = array();
    
    /**
     * Pobiera alias podmieniający.
     * Używane do podmiany nazwa klas bez konieczności zmieniania klasy
     * w plikach źródłowych frameworku i dodatków.
     * @param $alias Alias wejściowy (podany w kodzie).
     * @return string Alias wyjściowy (faktycznie użyty).
     */
    public static function getAliasOfAlias($alias) {
        if (isset(self::$aliasesOfAliases[$alias])) {
            return self::$aliasesOfAliases[$alias];
        } else {
            return $alias;
        }
    }
    
    /**
     * Ustawia alias podmieniający.
     * @param string $oldAlias Alias, który ma zostać podmieniony.
     * @param string $newAlias Alias docelowy.
     */
    public static function setAliasOfAlias($oldAlias, $newAlias) {
        self::$aliasesOfAliases[$oldAlias] = $newAlias;
    }
    
    /**
     * Tworzy aplikację.
     * Zamiast korzystać z klasy CWebApplication używa WebApplication.
     * @param array $config Cała konfiguracja aplikacji.
     * @return WebApplication Aplikacja.
     */
    public static function createWebApplication($config=null) 
    { 
            return self::createApplication('WebApplication',$config); 
    }
	
    public static function createConsoleApplication($config=null)
    {
            return self::createApplication('ConsoleApplication',$config);
    }
    
    /**
     * Tłumaczenie tesktu.
     * @param string $category Kategoria tłumaczeń.
     * @param string $message Tekst do przetłumaczenia.
     * @param array $params Parametry do podmiany po przetłumaczeniu.
     * @param string $source Źródło tłumaczeń (spośród zdefiniuwanych w config).
     * @param string $language Kod języka.
     * @return string Przetłumaczony tekst.
     */
    public static function t($category,$message,$params=array(),$source=null,$language=null) 
    {
        // prevent mistakes with null
        if ($params === null) {
            $params = array();
        }
        return parent::t($category,$message,$params,$source,$language); 
    }
    
    protected static $beginProfileTime = 0.0;
    
    public static function beginProfile($token, $category = 'application')
    {
        parent::beginProfile($token, $category);
        self::$beginProfileTime = microtime(true);
    }
    
    public static function endProfile($token, $category = 'application') 
    {
        $endProfileTime = microtime(true);
        parent::endProfile($token, $category);
        
        $time = $endProfileTime - self::$beginProfileTime;
        Yii::log('Execution time: '.number_format($time, 4).' sec.', CLogger::LEVEL_PROFILE, 'system.endProfile');
        if ($time > Yii::app()->db->profileTimeLimit) {
            Yii::log('Last query seems to be slow.', CLogger::LEVEL_WARNING, 'system.endProfile');
        }
    }
	
	public static function consoleLog($string)
	{
		echo '<script>console.log('.json_encode($string).');</script>';
	}
	
	public static function consoleInfo($string)
	{
		echo '<script>console.info('.json_encode($string).');</script>';
	}
    
}
