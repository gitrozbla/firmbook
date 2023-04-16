<?php
/**
 * Obsługa zdarzenia braku tłumaczenia.
 *
 * Wysyła informacje do logów.
 * 
 * @category components
 * @package components\other
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class LanguageEventHandler
{
    /**
     * Funkcja obsługująca darzenie braku tłumaczenia.
     *
     * Wysyła informacje do logu.
     *
     * @param CEvent $event Event. Zawiera informacje o kategorii, języku, tekście.
     */
    static function handleMissingTranslation($event)
    {
        // jeżeli użytkownik używa natywnego języka systemu (angielski) to nie trzeba tłumaczyć
        if($event->language != Yii::app()->sourceLanguage)
        {
            // event class for this event is CMissingTranslationEvent    
            // so we can get some info about the message
            $text = implode("\n", array(
                    'Language: '.$event->language,
                    'Category: '.$event->category,
                    'Message: '.$event->message         
            ));
            // trace stack
            $e = new Exception();
            $text .= "\n".$e->getTraceAsString();
            Yii::log("\n".$text, CLogger::LEVEL_WARNING, 'Missing translation');

            // sending email... better not.
        }
    }
}

?>