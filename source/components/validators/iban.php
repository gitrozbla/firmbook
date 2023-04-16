<?php
/**
 * Walidator wysyłanego pliku.
 * 
 * Dodane wsparcie base64, używane przez EditableSaver
 * @see EditableSaver
 *
 * @category components
 * @package components\validators
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class iban extends CValidator 
{
    /**
     * Kod domyślnego kraju (np. pl) dla generowania IBAN.
     * @var string 
     */
    public $country = null;

    /**
     * Weryfikuje IBAN po stronie serwera.
     * Jeśli kod nie posiada kodu kraju, zostaje użyty domyślny.
     * @see iban::country
     * W przypadku nie przejścia testu dodaje błąd.
     * @see CValidator::addError()
     * @param object $object Instancja zawierająca sprawdzany atrybut.
     * @param string $attribute Nazwa atrybutu.
     */
    protected function validateAttribute($object, $attribute) {
        
        $number = $object->$attribute;
        
        $number = str_replace(array(' ', '+', '-', '.', ','), '', $number);
        
        if (empty($number)) {
            return;
        }
        
        // letters are allowed!
        /*if (ctype_digit($number) == false) {
            $this->addError($object, $attribute, 
                    Yii::t('validators', 'Only numbers are allowed.')
                    );
        }*/
        
        // country prefix
        $len = strlen($number);
        if ($len == 26) {
            if ($this->country == null) {
                $number = (Yii::app()->language).$number;
            } else {
                $number = ($this->country).$number;
            }
            $len = 28;
        }
        
        // length
        if ($len != 28) {
            if ($len < 28) {
                $this->addError($object, $attribute, 
                        Yii::t('validators', 'Account number is to short.')
                        );
            } else {
                $this->addError($object, $attribute, 
                        Yii::t('validators', 'Account number is to long.')
                        );
            }
        } else {
            $number = strtoupper($number);
        }
        
        // number verify
        if ($this->is_valid_iban($number) == false) {
            $this->addError($object, $attribute, 
                        Yii::t('validators', 'Account number is invalid.')
                        );
        }
        
        for ($i=24; $i>3; $i-=4) {
            $number = substr_replace($number, ' ',  $i, 0);
        }
        $number = substr_replace($number, ' ',  2, 0);
        
        $object->$attribute = $number;
        
    }
    
    /**
     * Weryfukuje IBAN
     * @staticvar array $charmap Wagi liter w IBAN.
     * @param string $iban IBAN.
     * @return boolean Czy IBAN poprawny
     */
    protected function is_valid_iban($iban) {
        static $charmap = array (
            'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29, 'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34, 'Z' => 35,
        );
        if (!preg_match("/[A-Za-z0-9]+/", $iban)) {
            return false;
        }
        $iban = substr($iban, 4) . substr($iban, 0, 4);
        $iban = strtr($iban, $charmap);
        return $this->my_bcmod($iban, 97) === 1 ? true : false;
    }
    
    /**
     * Dzielenie modulo dużych liczb.
     * Duże liczby nie są dzielone poprawnie operatorem %.
     * Używane przez is_valid_iban.
     * @see iban::is_valid_iban()
     * @param string $x Dzielna.
     * @param string $y Dzielnik.
     * @return int Reszta modulo z dzielenia.
     */
    protected function my_bcmod( $x, $y ) 
    { 
        // how many numbers to take at once? carefull not to exceed (int) 
        $take = 5;     
        $mod = ''; 

        do 
        { 
            $a = (int)$mod.substr( $x, 0, $take ); 
            $x = substr( $x, $take ); 
            $mod = $a % $y;    
        } 
        while ( strlen($x) ); 

        return (int)$mod; 
    } 
}
