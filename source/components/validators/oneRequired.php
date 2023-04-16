<?php
/**
 * Walidator wypełnienia przynajmniej jednego pola.
 * 
 * @category components
 * @package components\validators
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class oneRequired extends CValidator 
{
    /**
     * Lista pozostałych atrybutów
     * @var array
     */
    public $others = array();
    /**
     * Zastępczy komunikat o błędzie.
     * @var string
     */
    public $message = null;

    /**
     * Weryfikacja atrybutu po stronie serwera
     * W przypadku nie przejścia testu dodaje błąd.
     * @see CValidator::addError()
     * @param object $object Instancja zawierająca sprawdzany atrybut.
     * @param string $attribute Nazwa atrybutu.
     */
    protected function validateAttribute($object, $attribute) {
        if (!empty($object->$attribute)) {
            return;
        }
        
        if (!is_array($this->others)) {
            $others = array($this->others);
        } else {
            $others = $this->others;
        }
        
        foreach ($others as $other) {
            if (!empty($object->$other)) {
                return;
            }
        }

        $message = $this->message ? $this->message : Yii::t('validators', 'At least one input must be filled.');
        $this->addError($object, $attribute, '(1) '.$message);
        $i = 2;
        foreach ($others as $other) {
            $this->addError($object, $other, '('.($i++).')');
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
        if (!is_array($this->others)) {
            $items = array($this->others);
        } else {
            $items = $this->others;
        }
        $items []= $attribute;
        
        $message = $this->message ? $this->message : Yii::t('validators', 'At least one input must be filled.');
        
        return "
            var found = false;
            var model = this.model;
            $(['".implode("','", $items)."']).each(function(){
                if ($('input#'+model+'_'+this).val() != '') {
                    found = true;
                    return false;
                }
            });
            if(found == false) {
                messages.push(".CJSON::encode($message).");
            }
        ";
    }
}
