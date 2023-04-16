<?php

/**
 * Edytowalne pole.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableSelect3 extends EditableField 
{
    public $select3Escape = true;
    
    /**
     * Inicjuje widget edytowalnego pola.
     */
    public function init() 
    {
        $this->type = 'select2';
        
        if ($this->apply && is_string($this->source)) {
            // only when in ajax mode
            
            $select2Options = array(
                'ajax' => array(
                    'quietMillis' => 500,
                ),
            );
            
            if ($this->select2 == null) {
                $this->select2 = $select2Options;
            } else if (!isset($this->select2['ajax'])) {
                $this->select2['ajax'] = $select2Options['ajax'];
            } else if (!isset($this->select2['ajax']['quietMillis'])) {
                $this->select2['ajax']['quietMillis'] 
                        = $select2Options['ajax']['$select2Options'];
            }
        }
        
        if ($this->select3Escape == false) {
            $this->options['escape'] = false;
            if ($this->select2 == null) {
                $this->select2 = array();
            }
            $this->select2['escapeMarkup'] = 'js:function (m) { return m; }';
            $this->select2['minimumResultsForSearch'] = -1; // disable search!
        }
        
        parent::init();
    }
    
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField() 
    {
        $value = $this->_value;
        
        if (!empty($this->text)) {
            return $this->text;
        }
        if (empty($this->source)) {
            $this->source = array(
                '0' => Yii::t('editable', 'No'), 
                '1' => Yii::t('editable', 'Yes')
            );
        }

        $result = $this->source[$value];

        return $result;  
    }
    
}