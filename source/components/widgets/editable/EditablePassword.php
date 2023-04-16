<?php

/**
 * Edytowalne pole.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditablePassword extends EditableField 
{
    /**
     * Nazwa dodatkowego skryptu (dla klas dziedziczących).
     * @var string
     */
    protected $additionalScript = 'password';
            
    /**
     * Inicjuje widget edytowalnego pola.
     */
    public function init() 
    {
        $this->text = '********'; // for safety
        
        parent::init();
    }
    
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField() 
    {
        return '********';   
    }
    
}