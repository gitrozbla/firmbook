<?php
/**
 * Obiekt dostarcza modele na podstawie kryteriów.
 * 
 * Dodana funkcja grupowania rezultatów (zwraca tablicę tablic modeli).
 * 
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
// grouping support
class ActiveDataProvider extends CActiveDataProvider
{
    /**
     * Liczba modeli w jednej grupie.
     * False gdy modele mają być niepogrupowane (normalny wynik).
     * @var int|false
     */
    public $groupSize = false;
    
    /**
     * Ładowanie wyników.
     * Dodana obsługa grupowania wyników.
     * @return array Wynik wyszukiwania.
     */
    protected function fetchData()
    {
        if ($this->groupSize !== false) {
            $result = parent::fetchData();
            $result = array_chunk($result, $this->groupSize);
            return $result;
        } else {
            return parent::fetchData();
        }
    }
    
    /**
     * Pobiera i zwraca klucze główne znalezionych obiektów.
     * Dodana obsługa grupowania kluczy podobnie znalezionych obiektów.
     * @return array Tablica kluczy głównych.
     */
    protected function fetchKeys()
    {
        if ($this->groupSize !== false) {
            $keys=array();
            $i=1;
            foreach($this->getData() as $i=>$group)
            {
                foreach ($group as $data) {
                    $key=$this->keyAttribute===null ? $data->getPrimaryKey() : $data->{$this->keyAttribute};
                    $keys[$i]=is_array($key) ? implode(',',$key) : $key;
                    $i++;
                }
            }
            return $keys;
        } else {
            return parent::fetchKeys();
        }
    }

}
