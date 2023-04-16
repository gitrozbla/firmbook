<?php
/**
 * Walidator numeru telefonu.
 * 
 * @category components
 * @package components\validators
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class phone extends CValidator 
{
    /**
     * Weryfikacja atrybutu po stronie serwera
     * W przypadku nie przejścia testu dodaje błąd.
     * @see CValidator::addError()
     * @param object $object Instancja zawierająca sprawdzany atrybut.
     * @param string $attribute Nazwa atrybutu.
     */
    protected function validateAttribute($object, $attribute) {
        
        $value = $object->$attribute;
        if ($value == '') {
            return;
        }
        
        $numbers = 0;
        $length = strlen($value);
        $allowed = array('+', '-', ' ', '(', ')');
        for ($i=0; $i<$length; $i++) {
            if(is_numeric($value[$i])) {
                $numbers++;
            } else if (!in_array($value[$i], $allowed)) {
                $this->addError($object, $attribute, 
                        Yii::t('validators', 'Phone number contains incorrect characters.')
                        );
                return;
                
            }
        }
        
        if ($numbers < 9) {
            $this->addError($object, $attribute, 
                    Yii::t('validators', 'Phone number is too short.')
                    );
        } else if ($numbers > 15) {
            $this->addError($object, $attribute, 
                    Yii::t('validators', 'Phone number is too long.')
                    );
        }
    }

    /**
     * Walidacja atrybutu po stronie przeglądarki.
     * W przypadku nie przejścia testu dodaje błąd w kodzie Javascript.
     * @param object $object Instancja zawierająca sprawdzany atrybut.
     * @param string $attribute Walidacja w Javascript.
     */
    public function clientValidateAttribute($object, $attribute)
    {
        return "
            if (value != '') {
                var numbers = 0;
                var length = value.length;
                var allowed = ['+', '-', ' ',  '(', ')'];
                for(var i=0; i<length; i++) {
                    if (!isNaN(value.charAt(i))) {
                       numbers++;
                    } else if ($.inArray(value[i], allowed) == -1) {
                        messages.push(".CJSON::encode(Yii::t('validators', 'Phone number contains incorrect characters.')).");
                        return;
                    }
                }
                if (numbers < 9) {
                    messages.push(".CJSON::encode(Yii::t('validators', 'Phone number is too short.')).");
                } else if (numbers > 15) {
                    messages.push(".CJSON::encode(Yii::t('validators', 'Phone number is too long.')).");
                }
            }
        ";
    }
}
