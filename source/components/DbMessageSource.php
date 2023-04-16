<?php
/**
 * Źródło tłumaczeń pochodzące z bazy danych.
 * 
 * Dodana możliwość tłumaczenia w drugą stronę (kategorie z przedrostkiem 'inv.').
 * 
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class DbMessageSource extends CDbMessageSource 
{
    /**
     * Ładowanie tłumaczeń z bazy.
     * Umożliwia załadowanie odwrotnej tablicy (array_flip) tłumaczeń 
     * przy podaniu przedrostka 'inv.' w kategorii.
     * @param string $category Kategoria.
     * @param string $language Kod języka.
     * @return array Wczytane wiadomości.
     */
    protected function loadMessagesFromDb($category,$language)
    {
        if (Func::startsWith($category, 'inv.')) {
            return array_flip(parent::loadMessagesFromDb(substr($category, 4),$language));
        } else {
            return parent::loadMessagesFromDb($category,$language);
        }
    }
    
}
