<?php

/**
 * Edytowalne pole.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditablePhone extends EditableField 
{   
    /**
     * Nazwa dodatkowego skryptu (dla klas dziedziczących).
     * @var string
     */
    protected $additionalScript = 'phone';
            
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField() 
    {
        return Html::link(
                $this->_value,
                'tel:'.str_replace(
                        array('+', '-', ' ', '(', ')'), 
                        '', 
                        $this->_value)
            );   
    }
    
}