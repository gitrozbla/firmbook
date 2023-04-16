<?php

/**
 * Edytowalne pole.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableToggle extends EditableCheckbox 
{
    public $toggleSize = 'mini';
    /**
     * Nazwa dodatkowego skryptu (dla klas dziedziczących).
     * @var string
     */
    protected $additionalScript = 'toggle';
    
    /**
     * Inicjuje widget edytowalnego pola.
     */
    public function init() 
    {
        $this->type = 'toggle';
        
        /*if (empty($this->source)) {
            $this->source = array(
                '0' => Yii::t('editable', 'No'), 
                '1' => Yii::t('editable', 'Yes')
            );
        }*/ // same in EditableCheckbox
        
        $this->options = array_merge($this->options, array(
            'size' => $this->toggleSize,
        ));
        
        Yii::app()->clientScript->registerCssFile(Yii::app()->homeUrl.'js/vendor/bootstrap-switch/css/bootstrap2/bootstrap-switch.min.css');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->homeUrl.'js/vendor/bootstrap-switch/js/bootstrap-switch.min.js');
        
        parent::init();
    }
    
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField() 
    {
        return parent::renderField();   
    }
    
}