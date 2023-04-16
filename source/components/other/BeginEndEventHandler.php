<?php
/**
 * Obsługa zdarzenia zaczynania i kończenia przetwarzania zapytania.
 *
 * M.in. kompresuje stronę i zabezpiecza przed kolizjami 'id' w html przy zapytaniach typu ajax.
 * 
 * @category components
 * @package components\other
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class BeginEndEventHandler 
{
	/**
	 * Funkcja wykonywana przed wysłaniem jakiegokolwiek znaku do przeglądarki.
	 *
	 * @param CEvent $event Event. Tutaj ignorowany.
	 */
	public static function beginRequest($event)
	{
		// wczytanie tablicy uprawnień (cache)
		//Yii::app()->user->loadRightsCache();
		
		// kompresja strony
		if (Yii::app()->params->compress) {
			if (extension_loaded('zlib')) {
				ini_set('zlib.output_compression','On');
			}
			 else {
				return ob_start("ob_gzhandler");	// małpa, aby uniknąć informacji o braku możliwości włączenia kompresji.
			}
		}
		
		// zapisywanie referrera w ciastku
		if (!isset($_COOKIE['referrer']) && isset($_SERVER['HTTP_REFERER'])) {
			setcookie('referrer', $_SERVER['HTTP_REFERER'], time() + 30 * 86400, '/');	// 30 dni
		}
	}
	
	/**
	 * Funkcja wykonywania na sam koniec przetwarzania zapytania.
	 *
	 * @param CEvent $event Event. Tutaj ignorowany.
	 */
	public static function endRequest($event)
	{
		// zapisanie tablicy uprawnień (cache)
		//Yii::app()->user->saveRightsCache();
		
		// kompresja strony
		if (Yii::app()->params->compress) {
			if (extension_loaded('zlib') == false) {
				return ob_end_flush();
			}
		}
	}
}

