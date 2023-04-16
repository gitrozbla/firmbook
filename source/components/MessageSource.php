<?php
/**
 * Klasa bazowa dla źródła tłumaczenia.
 *
 * Dodana obsługa wydajnego tłumaczenia całej tablicy wiadomości oraz możliwość zwrócenia całej tablicy tłumaczeń.
 * Każda klasa tłumacząca musi dziedziczyć po tej klasie, a nie po CMessageSource!
 * 
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
abstract class MessageSource extends CMessageSource
{
	/** 
	 * Cache wiadomości.
	 * @var array
	 */
	private $_messages=array();
	
	//abstract public function getTranslationsFromSource($category,$language);
	
	/**
	 * Zwraca wszystkie tłumaczenia danej kategorii.
	 *
	 * Wykorzystywane m.in. w DbCriteria do tłumaczenia przy wyszukiwaniu, pośrednio przez klasę Yii.
	 * @see DbCriteria::translateAndCompare()
	 * @see Yii
	 *
	 * @param string $category Kategoria tłumaczeń.
	 * @param string $language Język.
	 *
	 * @return array Tłumaczenia ('tekst'=>'tłumaczenie).
	 */
	public function getTranslations($category,$language=null)
	{
		if($language===null)
			$language=Yii::app()->getLanguage();
		if($this->forceTranslation || $language!==$this->getLanguage()) {
			$key=$language.'.'.$category;
			if(!isset($this->_messages[$key]))
				$this->_messages[$key]=$this->loadMessages($category,$language); 
			return $this->_messages[$key];
		} else
			return null;
	}
	
	/**
	 * Tłumaczenie wiadomości.
	 *
	 * Funkcja przerobiona pod kątem obsługi wiadomości w array.
	 *
	 * @param string $category Kategoria tłumaczeń.
	 * @param string|array $message Wiadomość lub wiadomości do przetłumaczenia.
	 * @param string $language Jezyk tłumaczenia.
	 *
	 * @return string|array Przetłumaczony tekst.
	 **/
	protected function translateMessage($category,$message,$language)
	{
		if(empty($message)) return $message;
		if (!is_array($message)) return parent::translateMessage($category,$message,$language);
		else {
			foreach($message as $key=>$value) {
				$message[$key] = parent::translateMessage($category,$message[$key],$language);
			}
			return $message;
		}
	}
	
        /**
         * Sprawdza, czy kategoria istnieje.
	 * Potrzebne dla selektywnego tłumaczenia wartości parametrów get.
	 * Nie wyrzuca błędu do logów, jeżeli kategoria nie istnieje.
	 * Klasy pochodne powinny posiadać implementację.
         */
	abstract public function translateCategoryExists($category,$language);
}

?>