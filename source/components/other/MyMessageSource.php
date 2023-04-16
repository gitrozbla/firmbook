<?php
/**
 * Źródło tłumaczeń.
 *
 * CPhpMessageSource represents a message source that stores translated messages in PHP scripts.
 * Instrukcja z wersji oryginalnej:
 * @link http://www.yiiframework.com/doc/api/1.1/CPhpMessageSource
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2011 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 *
 * @category components
 * @package components\other
 * @author BAI
 * @copyright (C) 2014 BAI
 *
 * Tłumaczenia pochodzą z plików, ale możliwe jest również pobieranie tłumaczenia odwrotnego.
 * Klasa oparta jest na źródle Yii.
 * Nie może ona dziedziczyć po CPhpMessageSource, ponieważ, tamta dziedziczy po CMessageSource, a my mamy własną MessageSource.
 * @see CPHPMessageSource
 * @see MessageSource
 */
class MyMessageSource extends MessageSource
{
	const CACHE_KEY_PREFIX='Yii.MyMessageSource.';
	
	/**
	 * Maksymalny czas pamiętania w cache.
	 * @var int
	 */
	public $cachingDuration=0;
	/** 
	 * ID komponentu klasy cache.
	 * @var string 
	 */
	public $cacheID='cache';
	/** 
	 * Ścieżka katalogu aplikacji.
	 * @var string
	 */
	public $basePath;
	/** 
	 * Kopie plików z tłumaczeniami (po wczytaniu, na czas sesji).
	 * @var array
	 */
	protected $_files=array();

	/**
	 * Inicjuje komponent.
	 *
	 * Ustawia $basePath.
	 */
	public function init()
	{
		parent::init();
		if($this->basePath===null)
			$this->basePath=Yii::getPathOfAlias('application.messages');
	}

	/**
	 * Pobiera i zwraca zawartość pliku z tłumaczeniem.
	 *
	 * Parsuje $category i poszukuje pliku.
	 * 
	 * @param string $category Kategoria tłumaczeń.
	 * @param string $language Język tłumaczenia.
	 *
	 * @return array Tłumaczenia pobrane bezpośrednio ze źródła.
	 */
	public function getMessageFile($category,$language)
	{
		/*if (substr($category, 0, 4)=='inv.') {
			$category = substr($category, 4);
			$invert = true;
		}*/
		
		if(!isset($this->_files[$category][$language]))
		{
			if(($pos=strpos($category,'.'))!==false)
			{
				$moduleClass=substr($category,0,$pos);
				$moduleCategory=substr($category,$pos+1);
				$class=new ReflectionClass($moduleClass);
				$result = dirname($class->getFileName()).DIRECTORY_SEPARATOR.'messages'.DIRECTORY_SEPARATOR.$language.DIRECTORY_SEPARATOR.$moduleCategory.'.php';
			}
			else
				$result = $this->basePath.DIRECTORY_SEPARATOR.$language.DIRECTORY_SEPARATOR.$category.'.php';
		}
		if (isset($result))
		{
			//if (isset($invert)) $result = array_flip($result);
			$this->_files[$category][$language] = $result;
		}
		return $this->_files[$category][$language];
	}
	
	/**
	 * Zwraca tłumaczenie danej kategorii.
	 * 
	 * Jeżeli cache nie zawiera tłumaczeń dla tego języka i tej kategorii to wykorzystuje funkcję MyMessageSource::getMessageFile().
	 * @see MyMessageSource::getMessageFile()
	 * Zawiera obsługę pobierania odwróconego słownika (prefix 'inv.' w $category.
	 * Tłumaczenie odwracane jest gunkcją array_flip() i cache`owane osobno.
	 * 
	 * @param string $category Kategoria tłumaczeń.
	 * @param string $language Język tłumaczenia.
	 *
	 * @return array Tłumaczenia pobrane z cache lub funkcjąMyMessageSource::getMessageFile().
	 */
	protected function loadMessages($category,$language)
	{
		if (substr($category, 0, 4)=='inv.') {
			$messageFile=$this->getMessageFile(substr($category, 4),$language);
			$inv = true;
		} else {
			$messageFile=$this->getMessageFile($category,$language);
			$inv = false;
		}
		//$messageFile=$this->getTranslationsFromSource($category,$language);
		
		if($this->cachingDuration>0 && $this->cacheID!==false && ($cache=Yii::app()->getComponent($this->cacheID))!==null)
		{
			$key=self::CACHE_KEY_PREFIX . $messageFile . ($inv ? '_inv' : '');	// klucz cache dla odwróconego słownika ma dopisane inv
			if(($data=$cache->get($key))!==false)
				return unserialize($data);
		}

		if(is_file($messageFile))
		{
			$messages=include($messageFile);
			if(!is_array($messages)) {
				$messages=array();
			} else if ($inv){	// generowanie odwróconego słownika
				$messages = array_flip($messages);
			}
			if(isset($cache))
			{
				$dependency=new CFileCacheDependency($messageFile);
				$cache->set($key,serialize($messages),$this->cachingDuration,$dependency);
			}
			return $messages;
		}
		else
			return array();
	}
	
        /**
         * Sprawdza, czy kategoria istnieje.
	 * Potrzebne dla selektywnego tłumaczenia wartości parametrów get.
	 * Nie wyrzuca błędu do logów, jeżeli kategoria nie istnieje.
         * @param string $category Kategoria tłumaczeń.
         * @param string $language Język.
         * @return boolean Rezultat.
         */
	public function translateCategoryExists($category,$language)
	{
		if ($this->loadMessages($category,$language)) return true;
		else return false;
	}

}

?>