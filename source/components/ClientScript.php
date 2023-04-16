<?php
/**
 * Klasa zarządzająca skryptami i sekcją <head>.
 *
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class ClientScript extends CClientScript
{
    /**
     * Czy CssCrush zainicjowany.
     * @see 
     * @var boolean
     */
    protected static $_cssCrushLoaded = false;
    
    /**
     * Przetwarza i kompresuje plik Crush CSS.
     * @see http://the-echoplex.net/csscrush/
     * Nowy plik ma rozszerzenie .crush.css .
     * Plik jest tworzony w tym samym katalogu co oryginalny.
     * Jeśli plik się nie zmienił, nowy nie jest generowany ponownie.
     * 
     * @param string $path Ścieżka do pliku wejściowego.
     * @param string $media Atrybut media tagu script.
     * @param boolean $compress Czy kompresować dane (minify).
     * @return CClientScript Zwraca sam siebie. Tzw. chaining support.
     */
    public function registerCssCrushFile($path, $media='')
    {
        $compress = !YII_DEBUG;

        if (self::$_cssCrushLoaded == false) {
            // autoload swap
            spl_autoload_unregister(array('YiiBase','autoload'));
            require(dirname(__FILE__).'/../../extensions/css-crush/CssCrush.php');
            spl_autoload_register(array('YiiBase','autoload'));
            
            self::$_cssCrushLoaded = true;
        }

        $file = csscrush_file($path, array(
                'minify'=>$compress,
                'cache'=>$compress
            ));
        return parent::registerCssFile(
            $file->url,
            $media);		
    }
    
}
