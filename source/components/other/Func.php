<?php
/**
 * Funkcje dodatkowe, pomocne w różnych momentach.
 *
 * @category components
 * @package components\other
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class Func 
{
	/**
	 * Porównuje początki łańcuchów znaków.
	 *
	 * @param string $haystack Przeszukiwany łańcuch znaków.
	 * @param string $needle Poszukiwany fragment.
	 *
	 * @return boolean Czy oba łańcuchy zaczynają się tak samo.
	 */
	static public function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	/**
	 * Porównuje końcówki łańcuchów znaków.
	 *
	 * @param string $haystack Przeszukiwany łańcuch znaków.
	 * @param string $needle Poszukiwany fragment.
	 *
	 * @return boolean Czy oba łańcuchy kończą się tak samo.
	 */
	static public function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		$start  = $length * -1; //negative
		return (substr($haystack, $start) === $needle);
	}
	
	/**
	 * Przetwarza tablicą z postaci  zwracanej z bazy do postaci potrzebnej dla list typu Dropdown.
	 *
	 * Tablicę w postaci array(array('keyName'=>'keyValue', 'varName'=>'varValue') przetwarza na array('keyValue'=>'varValue').
	 * Pierwsza postać to najczęściej wynik zapytania w stylu mysql_fetch_assoc(mysql_query('SELECT `id`, `value`...')).
	 * Po przetworzeniu otrzymujemy array('id'=>'value').
	 * Przydatne przy tworzeniu list Dropdown.
	 * @see CActiveForm::dropDownList()
	 *
	 * @param string $keyName Klucz w tablicy wejściowej, którego wartości będą kluczami w tablicy wyjściowej.
	 * @param string $valueName Klucz w tablicy wejściowej, którego wartości będą wartościami w tablicy wyjściowej.
	 * @param array $inputArray Tablica wejściowa.
	 *
	 * @return array Przetworzona tablica.
	 */
	static public function createDropdownOptions($keyName, $valueName, $inputArray)
	{
		foreach($inputArray as $item) {
			$output[$item[$keyName]] = $item[$valueName];
		}
		return $output;
	}
	
	/**
	 * Formatuje cyfrę, odzielając grupy cyfr przecinkami.
	 *
	 * @param string $number Cyfra w postaci łańcucha string.
	 *
	 * @return string Sformatowana cyfra.
	 */
	static public function formatNumber($number)
	{
		$len = strlen($number);
		if($len <= 3) return $number;
		
		$rest = (int)((int)$len % (int)3);
		if ($rest == 0) return implode(",", str_split($number, 3));
		
		$begin = substr($number, 0, $rest);
		$end = substr($number, $rest);
		$end = implode(",", str_split($end, 3));
		
		return $begin.','.$end;
	}
        
        /**
         * Generuje łańcuch string na podstawie tablicy.
         * Używane dla generowania uri, css i innych.
         * @param array $array Tablica asocjacyjna do konstrukcji łańcucha.
         * @param string $arrayMergeSeparator Separator pomiędzy częściami tablicy.
         * @param string $itemMergeSeparator Separator pomiędzy kluczem a wartością.
         * @param bool $urlEncode Czy użyć urlencode dla każdego elementu.
         * @return string Połączony łańcuch.
         */
        static public function buildString($array, $arrayMergeSeparator='&', $itemMergeSeparator='=', $urlEncode=true) 
        {
            if ($urlEncode) {
                foreach($array as $key=>$value) {
                    $array[$key] = urlencode($key).$itemMergeSeparator.urlencode($value);
                }
            } else {
                foreach($array as $key=>$value) {
                    $array[$key] = $key.$itemMergeSeparator.$value;
                }
            }
            return implode($arrayMergeSeparator, $array);
        }
        
        
        // generates random string
        /**
         * Generuje losowy łańcuch znaków.
         * @param int $length Długość łańcucha.
         * @param string $charset Zestaw dopuszczalnych znaków.
         * @return string Wygenerowany łańcuch.
         */
        static public function randomString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
        {
            $str = '';
            $count = strlen($charset);
            while ($length--) {
                $str .= $charset[mt_rand(0, $count-1)];
            }
            return $str;
        }
	
}

?>