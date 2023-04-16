<?php

/**
 * Edytowalne pole.
 * 
 * @category components
 * @package components\widgets
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class EditableDatetime extends EditableField 
{
    /**
     * Format daty.
     * @var string mysql_datetime/unix_timestamp
     */
    public $dateSourceFormat = 'mysql_datetime';// 'unix_timestamp';
    
    /**
     * Inicjuje widget edytowalnego pola.
     */
    public function init() 
    {
        $this->type = 'datetime';
        
        Yii::app()->clientScript->registerScriptFile('js/datepicker-fix.js',
            ClientScript::POS_HEAD);    // fix
        
        parent::init();
    }
    
    /**
     * Renderuje zawartość pola zgodnie z zadanymi parametrami.
     * @return string Zawartość pola.
     */
    public function renderField() 
    {
        $value = $this->_value;
        
        $dateFormatter = Yii::app()->dateFormatter;
        if ($this->dateSourceFormat == 'mysql_datetime') {
            $value = CDateTimeParser::parse($value, 'yyyy-M-d H:mm:ss');
        }

        // php 5.2 required here
        $timezone = new DateTimeZone(Yii::app()->timezone);
        $timeOffset = $timezone->getOffset(new DateTime('now', $timezone)) / 3600;
        $timezoneText = ' (UTC+'.$timeOffset.')';

        if (!empty($value) and $value != 0) {
            return $dateFormatter->format('yyyy-MM-dd H:mm', $value).$timezoneText;// format fix
        } else {
            return $this->emptytext;
        }
    }
    
    /**
     * Tworzy opcje Javascript i zapisuje je do EditableField::$options.
     * Dodana obsługa języka w DatePicker.
     */
    public function buildJsOptions() {
        if (!isset($this->options['datetimepicker']['language'])) {
            $this->options['datetimepicker']['language'] = substr(Yii::app()->getLanguage(), 0, 2);
        }
        parent::buildJsOptions();
    }
    
}